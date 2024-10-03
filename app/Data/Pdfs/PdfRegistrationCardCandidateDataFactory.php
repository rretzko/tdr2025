<?php

namespace App\Data\Pdfs;

use App\Models\Events\Event;
use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionConfigDate;
use App\Models\Events\Versions\VersionTimeslot;
use App\Models\Pronoun;
use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\Students\Student;
use App\Models\Students\VoicePart;
use App\Models\User;
use App\Services\CalcGradeFromClassOfService;
use App\Services\ConvertToUsdService;
use App\Services\FullNameService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PdfRegistrationCardCandidateDataFactory
{
    private array $dto = [];
    private int $versionId;

    public function __construct(private readonly Candidate $candidate)
    {
        $this->versionId = $this->candidate->version_id;

        $this->init();
    }

    private function init(): void
    {
        $this->dto['versionName'] = $this->getVersionName();
        $this->dto['rows'][] = $this->getRow();
    }

    private function getVersionName(): string
    {
        return Version::find($this->candidate->version_id)->name;
    }

    private function getRow(): array
    {
        $user = $this->candidate->student->user;
        $school = School::find($this->candidate->school_id);

        return [
            'email' => $user->email,
            'fullNameAlpha' => $user->fullNameAlpha,
            'ref' => $this->candidate->ref,
            'rooms' => $this->getRooms(),
            'schoolId' => $school->id,
            'schoolName' => $school->name,
            'timeslot' => $this->getTimeslot(),
            'voicePartDescr' => $this->getVoicePartDescr(),
        ];
    }

    private function getRooms(): array
    {
        $voicePartId = $this->candidate->voice_part_id;

        return DB::table('rooms')
            ->join('room_voice_parts', 'room_voice_parts.room_id', '=', 'rooms.id')
            ->where('rooms.version_id', $this->versionId)
            ->where('room_voice_parts.voice_part_id', $voicePartId)
            ->select('rooms.room_name AS roomName', 'rooms.order_by')
            ->orderBy('rooms.order_by')
            ->get()
            ->toArray();
    }

    private function getTimeslot(): string
    {
        $timeslot = VersionTimeslot::query()
            ->where('version_id', $this->versionId)
            ->where('school_id', $this->candidate->school_id)
            ->value('timeslot');

        return Carbon::createFromTimestamp($timeslot, 'America/New_York')->format('g:i a');
    }

    private function getVoicePartDescr(): string
    {
        return VoicePart::find($this->candidate->voice_part_id)
            ->descr;
    }

    public function getDto(): array
    {
        return $this->dto;
    }
}
