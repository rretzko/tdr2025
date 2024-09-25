<?php

namespace App\Services;

use App\Models\Events\Versions\Participations\Application;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Recording;
use App\Models\Events\Versions\Participations\Signature;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionConfigRegistrant;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Can;

class CandidateStatusService
{
    private static bool $applicationDownloaded = false;
    private static bool $signaturesVerified = false;
    private static bool $recordingsApproved = false;

    /**
     * Status definitions:
     * - eligible: Candidate is in the right grade level.
     *      - Candidate eligibility is determined at the point of creation
     * - engaged: Application downloaded, recording uploaded, signatures have been verified
     * - pre-registered: TBD
     * - prohibited: Candidate has been designated as prohibited from the current version by the version management
     * - registered: Candidate has the following:
     *      - Signatures verified
     *      - If file uploads are required, all files have been uploaded
     *      - If file uploads are required, all files have been approved
     * - removed: Candidate has left the school or program and designated as removed by the sponsoring teacher
     * - withdrew: Candidate has voluntarily withdrawn from the version
     */
    public static function getStatus(Candidate $candidate): string
    {
        $immutables = ['prohibited', 'removed', 'withdrew'];

        //early exit
        if (in_array($candidate->status, $immutables)) {
            return $candidate->status;
        }

        Log::info(__METHOD__.': '.__LINE__);
        Log::info('*** candidate status @ start: '.$candidate->status.' ***');

        //evaluate registration status conditions
        self::$applicationDownloaded = self::hasDownloadedApplication($candidate);
        self::$signaturesVerified = self::hasSignatures($candidate);
        self::$recordingsApproved = self::hasApprovedRecording($candidate);

        //determine registration status
        $status = self::getRegistrationStatus($candidate);

        //update $candidate if status is changed
        if ($candidate->status !== $status) {
            $candidate->update(['status' => $status]);
        }
        Log::info('*** status @ end: '.$status.' ***');
        Log::info('*** candidate status @ end: '.$candidate->status.' ***');
        return $status;
    }

    /**
     * registered: Candidate has the following:
     *      - Application downloaded
     *      - Signatures verified
     *      - If file uploads are required, all files have been approved
     * @return string
     */
    private static function getRegistrationStatus(Candidate $candidate): string
    {
        $requirements = self::getVersionRequirements($candidate);
        $hasRequirementsCount = 0;

        if (in_array('applicationDownloaded', $requirements) && self::$applicationDownloaded) {
            $hasRequirementsCount++;
        }

        if (in_array('recordingsApproved', $requirements) && self::$recordingsApproved) {
            $hasRequirementsCount++;
        }

        if (in_array('signatureVerified', $requirements) && self::$signaturesVerified) {
            $hasRequirementsCount++;
        }

        if (count($requirements) == $hasRequirementsCount) {
            return 'registered';
        }

        //partial completion of requirements
        if ($hasRequirementsCount && ($hasRequirementsCount < count($requirements))) {
            return 'engaged';
        }

        //default
        return 'eligible';
    }

    private static function getVersionRequirements(Candidate $candidate): array
    {
        $versionId = $candidate->version_id;
        $vca = VersionConfigAdjudication::where('version_id', $versionId)->first();
        $vcr = VersionConfigRegistrant::where('version_id', $versionId)->first();

        $requirements = [];
        $requirements[] = 'signatureVerified';

        $applicationDownloaded = (bool) $vcr->eapplication;
        $recordingsApproved = (bool) $vca->upload_count;

        if (!$applicationDownloaded) {
            $requirements[] = 'applicationDownloaded';
        }

        if ($recordingsApproved) {
            $requirements[] = 'recordingsApproved';
        }

        return $requirements;
    }

    private static function hasDownloadedApplication(Candidate $candidate): bool
    {
        //early exit; version uses eApplication, no download necessary
        $vcr = VersionConfigRegistrant::where('version_id', $candidate->version_id)->first();
        if ($vcr->eapplication) {
            return true;
        }

        return Application::where('candidate_id', $candidate->id)
            ->where('downloads', '>', 0)
            ->exists();
    }

    private static function hasApprovedRecording(Candidate $candidate): bool
    {
        $vca = VersionConfigAdjudication::where('version_id', $candidate->version_id)->first();

        $expectedUploads = $vca->upload_count;

        //early exit : if no uploads are required, default to true
        if ($expectedUploads == 0) {
            return true;
        }

        $recordingCount = Recording::query()
            ->where('candidate_id', $candidate->id)
            ->where('version_id', $candidate->version_id)
            ->whereNotNull('approved')
            ->count();

        return ($expectedUploads == $recordingCount);
    }

    private static function hasSignatures(Candidate $candidate): bool
    {
        $eApplication = VersionConfigRegistrant::where('version_id', $candidate->version_id)
            ->first()
            ->eapplication;
        Log::info(__METHOD__.': '.__LINE__);
        Log::info('*** eApplication: '.$eApplication);
        $roles = ($eApplication)
            ? ['guardian', 'student']
            : ['teacher'];
        Log::info('*** roles: '.serialize($roles));
        return self::checkSignatures($candidate, $roles);
    }

    /**
     * if any check fails, return false,
     * else return true
     * @param  Candidate  $candidate
     * @param  array  $roles
     * @return bool
     */
    private static function checkSignatures(Candidate $candidate, array $roles): bool
    {

        Log::info(__METHOD__.': '.__LINE__);
        foreach ($roles as $role) {

            Log::info('*** sql: '.Signature::query()
                    ->where('candidate_id', $candidate->id)
                    ->where('role', $role)
                    ->where('signed', 1)
                    ->toRawSql().' ***');
            Log::info('*** result: '.Signature::query()
                ->where('candidate_id', $candidate->id)
                ->where('role', $role)
                ->where('signed', 1)
                ->first() ?? 'not found'.' ***');
            if (!Signature::query()
                ->where('candidate_id', $candidate->id)
                ->where('role', $role)
                ->where('signed', 1)
                ->first()) {

                return false;
            }
        }

        return true;
    }
}
