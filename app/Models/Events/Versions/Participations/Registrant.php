<?php

namespace App\Models\Events\Versions\Participations;

use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Services\CalcSeniorYearService;
use App\Services\ConvertToUsdService;
use App\Services\CoTeachersService;
use App\ValueObjects\TotalStudentRegistrationPayments;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Registrant
{
    private array $coTeacherIds = [];
    private Collection $registrants;
    private int $seniorYear = 0;

    public function __construct(
        private readonly int $schoolId,
        private readonly int $versionId,
    ) {
        $this->coTeacherIds = CoTeachersService::getCoTeachersIds();
        $this->registrants = $this->getRegistrants();
        $seniorYearService = new CalcSeniorYearService();
        $this->seniorYear = $seniorYearService->getSeniorYear();
    }

    public function getCountOfRegistrants(): int
    {
        return ($this->schoolId)
            ? Candidate::query()
                ->where('version_id', $this->versionId)
                ->where('school_id', $this->schoolId)
                ->where('status', 'registered')
                ->count('id')
            : Candidate::query()
                ->where('version_id', $this->versionId)
                ->where('status', 'registered')
                ->count('id');
    }

    public function getCountOfRegistrantsCompleted(): int
    {
        $maxScoreCount = $this->getMaxScoreCount();

        return ($this->schoolId)
            ? AuditionResult::query()
                ->where('version_id', $this->versionId)
                ->where('school_id', $this->schoolId)
                ->where('score_count', '=', $maxScoreCount)
                ->count('id')
            : AuditionResult::query()
                ->where('version_id', $this->versionId)
                ->where('score_count', '=', $maxScoreCount)
                ->count('id');
    }

    public function getCountOfRegistrantsOverScored(): int
    {
        $maxScoreCount = $this->getMaxScoreCount();

        return ($this->schoolId)
            ? AuditionResult::query()
                ->where('version_id', $this->versionId)
                ->where('school_id', $this->schoolId)
                ->where('score_count', '>', $maxScoreCount)
                ->count('id')
            : AuditionResult::query()
                ->where('version_id', $this->versionId)
                ->where('score_count', '>', $maxScoreCount)
                ->count('id');
    }

    public function getCountOfRegistrantsWip(): int
    {
        $maxScoreCount = $this->getMaxScoreCount();

        return ($this->schoolId)
            ? AuditionResult::query()
                ->where('version_id', $this->versionId)
                ->where('school_id', $this->schoolId)
                ->where('score_count', '<', $maxScoreCount)
                ->count('id')
            : AuditionResult::query()
                ->where('version_id', $this->versionId)
                ->where('score_count', '<', $maxScoreCount)
                ->count('id');
    }

    public function getCountOfVoicePart(int $voicePartId): int
    {
        return $this->registrants->where('voice_part_id', $voicePartId)->count();
    }

    /**
     * @return array
     * @since 2024-Nov-12 Return candidates with auth()->id() as the sponsoring teacher (teacher_id)
     */
    public function getRegistrantArrayForEstimateForm($school = null): array
    {
        //$this->test();
        $schoolId = ($school)
            ? $school->id
            : $this->schoolId;

        $core = DB::table('candidates')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->where('version_id', $this->versionId)
            ->whereIn('teacher_id', $this->coTeacherIds)
            ->where('school_id', $schoolId)
            ->where('status', 'registered')
            ->where('candidates.teacher_id', auth()->id())
            ->select('candidates.id',
                'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix_name',
                'students.class_of',
                'voice_parts.descr AS voicePartDescr',
                DB::raw("( 12 - (students.class_of - $this->seniorYear)) AS grade"),
                'candidates.teacher_id',
            )
            ->get()
            ->toArray();

        $this->addPayments($core);

        return $core;
    }

    /**
     * creating synonym for naming consistencu
     * @return int
     */
    public function getRegistrantCount(): int
    {
        return $this->getCountOfRegistrants();
    }

    /**
     * Add payment detail to core array
     * @return void
     */
    private function addPayments(array &$core): void
    {
        $valueObject = new TotalStudentRegistrationPayments();

        foreach ($core as $row) {
            $row->payment = ConvertToUsdService::penniesToUsd($valueObject->getPayment($row->id));
        }
    }

    private function getMaxScoreCount(): int
    {
        $judgesPerRoomCount = VersionConfigAdjudication::where('version_id',
            $this->versionId)->first()->judge_per_room_count;
        $scoringFactorCount = $this->getScoreFactorCount();

        return ($scoringFactorCount * $judgesPerRoomCount);
    }

    private function getRegistrants(): Collection
    {
        return ($this->schoolId)
            ? Candidate::query()
                ->where('version_id', $this->versionId)
                ->where('school_id', $this->schoolId)
                ->where('status', 'registered')
                ->get()
            : Candidate::query()
                ->where('version_id', $this->versionId)
                ->where('status', 'registered')
                ->get();
    }

    private function getScoreFactorCount(): int
    {
        $count = ScoreFactor::where('version_id', $this->versionId)->count('id');

        if (!$count) {
            $eventId = Version::find($this->versionId)->event_id;
            $count = ScoreFactor::where('event_id', $eventId)->count('id');
        }

        return $count;
    }

    private function test(): void
    {

    }
}
