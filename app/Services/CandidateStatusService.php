<?php

namespace App\Services;

use App\Models\Events\Versions\Participations\Application;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Recording;
use App\Models\Events\Versions\Participations\Signature;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionConfigRegistrant;
use Illuminate\Validation\Rules\Can;

class CandidateStatusService
{
    private static string $status = 'eligible';

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
            self::$status = $candidate->status;
            return self::$status;
        }

        //is engaged
        self::hasDownloadedApplication($candidate); //will ALWAYS return true
        self::hasSignatures($candidate);
        self::hasRecording($candidate);

        //is registered
        self::isRegistered($candidate);

        //update $candidate if status is changed
        if (self::$status !== $candidate->status) {
            $candidate->update(['status' => self::$status]);
        }

        return self::$status;
    }

    private static function hasDownloadedApplication(Candidate $candidate): void
    {
        $downloaded = Application::query()
            ->where('candidate_id', $candidate->id)
            ->exists();

        self::$status = ($downloaded)
            ? 'engaged'
            : self::$status;
    }

    private static function hasRecording(Candidate $candidate): void
    {
        $vca = VersionConfigAdjudication::where('version_id', $candidate->version_id)->first();

        //early exit : no change to self::$status
        if ($vca->upload_count == 0) {
            return;
        }

        $recordingCount = Recording::query()
            ->where('candidate_id', $candidate->id)
            ->where('version_id', $candidate->version_id)
            ->count();

        self::$status = ($recordingCount)
            ? 'engaged'
            : self::$status;
    }

    private static function hasSignatures(Candidate $candidate): void
    {
        $eApplication = VersionConfigRegistrant::where('version_id', $candidate->version_id)
            ->first()
            ->eapplication;

        if ($eApplication) {

            self::$status = self::$status = self::checkSignatures($candidate, ['student', 'guardian'])
                ? 'engaged'
                : self::$status;

        } else { //paper app

            self::$status = self::checkSignatures($candidate, ['teacher'])
                ? 'engaged'
                : self::$status;
        }
    }

    private static function checkSignatures(Candidate $candidate, array $roles): bool
    {
        foreach ($roles as $role) {
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

    /**
     * registered: Candidate has the following:
     *      - Signatures verified
     *      - If file uploads are required, all files have been uploaded
     *      - If file uploads are required, all files have been approved
     * @param  Candidate  $candidate
     * @return void
     */
    private static function isRegistered(Candidate $candidate): void
    {
        //early exit
        if (self::$status !== 'engaged') { //confirm that appropriate signatures have been verified
            return;
        }

        //early exit
        $version = Version::find($candidate->version_id);

//        if ($version->upload_type === 'none') { //confirm that file uploads are expected
//            return;
//        }

        $versionConfig = VersionConfigAdjudication::where('version_id', $candidate->version_id)->first();
        if (!$versionConfig) {
            return;
        }

        $uploadCount = $versionConfig->upload_count;
        $approvedCount = Recording::query()
            ->where('candidate_id', $candidate->id)
            ->where('version_id', $candidate->version_id)
            ->whereNotNull('approved')
            ->count();
//dd(self::hasTeacherSignature($candidate));
        if (($uploadCount === $approvedCount) && self::hasTeacherSignature($candidate)) {
            self::$status = 'registered';
        }
    }

    private static function hasTeacherSignature($candidate): bool
    {
        $vcr = VersionConfigRegistrant::where('version_id', $candidate->version_id)->first();
        $eapplication = $vcr->eapplication;

        return ($eapplication)
            ? Signature::query()
                ->join('signatures AS guardian', 'guardian.candidate_id', '=', 'signatures.candidate_id')
                ->where('signatures.candidate_id', $candidate->id)
                ->where('signatures.role', 'student')
                ->where('signatures.signed', 1)
                ->where('guardian.role', 'guardian')
                ->where('guardian.signed', 1)
                ->exists()
            : Signature::query()
                ->where('signatures.candidate_id', $candidate->id)
                ->where('signatures.role', 'teacher')
                ->where('signatures.signed', 1)
                ->exists();
    }
}
