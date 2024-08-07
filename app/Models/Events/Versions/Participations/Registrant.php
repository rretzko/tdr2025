<?php

namespace App\Models\Events\Versions\Participations;

use App\Services\CalcSeniorYearService;
use App\Services\CoTeachersService;
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

    public function getCountOfVoicePart(int $voicePartId): int
    {
        return $this->registrants->where('voice_part_id', $voicePartId)->count();
    }

    public function getRegistrantArrayForEstimateForm(): array
    {
        return DB::table('candidates')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->where('version_id', $this->versionId)
            ->whereIn('teacher_id', $this->coTeacherIds)
            ->where('school_id', $this->schoolId)
            ->where('status', 'registered')
            ->select('candidates.id',
                'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix_name',
                'students.class_of',
                'voice_parts.descr AS voicePartDescr',
                DB::raw("(12 - (students.class_of - $this->seniorYear)) AS grade"))
            ->get()
            ->toArray();
    }

    public function getRegistrantCount(): int
    {
        return $this->registrants->count();
    }

    private function getRegistrants(): Collection
    {
        return Candidate::query()
            ->where('version_id', $this->versionId)
            ->where('school_id', $this->schoolId)
            ->where('status', 'registered')
            ->get();
    }
}
