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

class PdfRegistrationCardVoicePartDataFactory
{
    private array $dto = [];
    private int $versionId = 0;

    public function __construct(private readonly Version $version, private readonly VoicePart $voicePart)
    {
        $this->versionId = $this->version->id;

        $this->init();
    }

    private function init(): void
    {
        $this->dto['versionName'] = $this->getVersionName();
        $this->dto['rows'] = $this->getRows();
    }

    private function getVersionName(): string
    {
        return $this->version->name;
    }

    private function getRows(): array
    {
        $candidates = $this->sql();

        $rows = [];

        foreach ($candidates as $candidate) {

            $rows[] = [
                'email' => $candidate->email,
                'fullNameAlpha' => $candidate->fullNameAlpha,
                'ref' => $candidate->ref,
                'rooms' => $this->getRooms(),
                'schoolId' => $candidate->schoolId,
                'schoolName' => $candidate->schoolName,
                'timeslot' => $candidate->timeslot,
                'voicePartDescr' => $candidate->voicePartDescr,
            ];
        }

        return $rows;
    }

    private function sql()
    {
        return DB::table('candidates')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users', 'users.id', 'students.user_id')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->join('version_timeslots', function ($join) {
                $join->on('version_timeslots.version_id', '=', 'candidates.version_id')
                    ->on('version_timeslots.school_id', '=', 'schools.id');
            })
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->where('candidates.voice_part_id', $this->voicePart->id)
            ->select('candidates.ref',
                'users.email AS email',
                DB::raw("CONCAT(users.last_name, ', ', users.first_name, ' ', users.middle_name) AS fullNameAlpha"),
                'schools.id AS schoolId', 'schools.name AS schoolName',
                'version_timeslots.timeslot',
                'voice_parts.descr AS voicePartDescr',
            )
            ->orderBy('schoolName')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get()
            ->toArray();
    }

    private function getRooms(): array
    {
        $voicePartId = $this->voicePart->id;

        return DB::table('rooms')
            ->join('room_voice_parts', 'room_voice_parts.room_id', '=', 'rooms.id')
            ->where('rooms.version_id', $this->versionId)
            ->where('room_voice_parts.voice_part_id', $voicePartId)
            ->select('rooms.room_name AS roomName', 'rooms.order_by')
            ->orderBy('rooms.order_by')
            ->get()
            ->toArray();
    }

    public function getDto(): array
    {
        return $this->dto;
    }


}
