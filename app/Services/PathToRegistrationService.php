<?php

namespace App\Services;

use App\Models\Events\Event;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Recording;
use App\Models\Events\Versions\Participations\Signature;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionConfigRegistrant;
use App\Models\Students\Student;
use App\Models\Students\VoicePart;

/**
 * Return a <ul></ul> block with <li></li> elements that define the
 * steps required to move a candidate from eligible to registered
 * including checkmarks for steps completed.
 */
class PathToRegistrationService
{
    private static Candidate $candidate;
    private static Event $event;
    private static Version $version;

    public static function getPath(int $candidateId): string
    {
        self::$candidate = Candidate::find($candidateId);
        self::$version = self::$candidate->version;
        self::$event = self::$version->event;

        $str = '<ul>';
        $str .= self::candidateIsEligible();
        $str .= self::correctVoicePart();
        $str .= self::emergencyContact();
        $str .= self::emergencyContactPhoneMobile();
        $str .= self::applicationSignatures();
        if (self::$version->upload_type !== 'none') {
            $str .= self::recordingsUploaded();
            $str .= self::recordingsApproved();
        }
        $str .= '</ul>';

        return $str;
    }

    private static function candidateIsEligible(): string
    {
        $eligibles = ['eligible', 'engaged', 'no-app', 'preregistered', 'registered'];
        // not: prohibited, removed, withdrew

        return (in_array(self::$candidate->status, $eligibles))
            ? "<li class='text-green-600'>"
            .'Candidate is eligible to participate.'
            .'</li>'
            : "<li class='text-red-600'>"
            .'Candidate status ('.self::$candidate->status.') is ineligible to participate.'
            ."</li>";
    }

    private static function correctVoicePart(): string
    {
        $service = new EventEnsemblesVoicePartsArrayService(self::$event->eventEnsembles);

        $voicePartIds = $service->getArray();

        $voicePartDescr = VoicePart::find(self::$candidate->voice_part_id)->descr;

        return array_key_exists(self::$candidate->voice_part_id, $voicePartIds)
            ? '<li class="text-green-600">Correct voice part.</li>'
            : '<li class="text-red-600">Incorrect voice part ('.$voicePartDescr.').</li>';
    }

    private static function emergencyContact(): string
    {
        $student = Student::find(self::$candidate->student_id);

        return ($student->emergencyContacts()->count())
            ? '<li class="text-green-600">Emergency Contact found.</li>'
            : '<li class="text-red-600">No Emergency Contact found.</li>';
    }

    private static function emergencyContactPhoneMobile(): string
    {
        if (self::$candidate->student->emergencyContacts()->count()) {

            return (self::$candidate->student->emergencyContacts()
                ->whereNot('phone_mobile', "")
                ->count())
                ? '<li class="text-green-600">Emergency Contact cell phone available.</li>'
                : '<li class="text-red-600">No Emergency Contact cell phone found.</li>';
        }

        return '<li class="text-red-600">No Emergency Contact cell phone found.</li>';
    }

    private static function applicationSignatures(): string
    {
        $eApplication = VersionConfigRegistrant::where('version_id',
            self::$candidate->version_id)->first()->eapplication;

        if ($eApplication) {
            return (Signature::query()
                    ->where('candidate_id', self::$candidate->id)
                    ->where('signed', '1')
                    ->whereIn('role', ['guardian', 'student'])
                    ->count() === 2)
                ? '<li class="text-green-600">eApplication signatures verified.</li>'
                : '<li class="text-red-600">eApplication signatures missing or incomplete.</li>';
        } else {
            return (Signature::query()
                    ->where('candidate_id', self::$candidate->id)
                    ->where('signed', '1')
                    ->whereIn('role', ['teacher'])
                    ->count() === 1)
                ? '<li class="text-green-600">Signatures approved by teacher.</li>'
                : '<li class="text-red-600">Signatures unapproved by teacher.</li>';
        }
    }

    private static function recordingsUploaded(): string
    {
        $expected = VersionConfigAdjudication::where('version_id', self::$version->id)->first()->upload_count;
        $found = Recording::query()
            ->where('candidate_id', self::$candidate->id)
            ->count();

        return ($expected === $found)
            ? '<li class="text-green-600">Recordings uploaded.</li>'
            : '<li class="text-red-600">'.$found.' out of '.$expected.' recordings found.</li>';
    }

    private static function recordingsApproved(): string
    {
        $expected = VersionConfigAdjudication::where('version_id', self::$version->id)->first()->upload_count;
        $found = Recording::query()
            ->where('candidate_id', self::$candidate->id)
            ->whereNotNull('approved')
            ->count();

        return ($expected === $found)
            ? '<li class="text-green-600">Recordings approved.</li>'
            : '<li class="text-red-600">'.$found.' out of '.$expected.' approved recordings found.</li>';
    }

}
