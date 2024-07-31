<?php

namespace App\Services;

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
     * - applied: Signatures have been verified
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

        self::isApp($candidate);
        self::isRegistered($candidate);

        //update $candidate if status is changed
        if (self::$status !== $candidate->status) {
            $candidate->update(['status' => self::$status]);
        }

        return self::$status;
    }

    private static function isApp(Candidate $candidate): void
    {
        $eApplication = VersionConfigRegistrant::find($candidate->version_id)->eapplication;

        if ($eApplication) {

            self::$status = self::$status = self::checkSignatures($candidate, ['student', 'guardian'])
                ? 'applied'
                : self::$status;

        } else { //paper app

            self::$status = self::checkSignatures($candidate, ['teacher'])
                ? 'applied'
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
        if (self::$status !== 'applied') { //confirm that appropriate signatures have been verified
            return;
        }

        //early exit
        $version = Version::find($candidate->version_id);
        if ($version->upload_type === 'none') { //confirm that file uploads are expected
            return;
        }

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

        if ($uploadCount === $approvedCount) {
            self::$status = 'registered';
        }
    }
}
