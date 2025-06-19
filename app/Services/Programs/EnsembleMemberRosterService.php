<?php

namespace App\Services\Programs;

use App\Models\Ensembles\Members\Member;

class EnsembleMemberRosterService
{
    private array $students = [];

    public function __construct(private readonly int $ensembleId, private readonly int $schoolYear)
    {
        $this->init();
    }

    private function init(): void
    {
        $ensembleMembers = Member::query()
            ->where('ensemble_id', $this->ensembleId)
            ->where('school_year', $this->schoolYear)
            ->get();
    }

    public function getStudents(): array
    {
        return $this->students;
    }
}
