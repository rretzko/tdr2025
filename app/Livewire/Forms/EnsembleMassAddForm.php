<?php

namespace App\Livewire\Forms;

use App\Models\Ensembles\Members\Member;
use App\Models\Students\Student;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EnsembleMassAddForm extends Form
{
    public int $ensembleId = 0;
    public array $newMembers = [];
    public int $schoolId = 0;
    public int $schoolYear = 0;

    public function save(): void
    {
        foreach ($this->newMembers as $studentId) {

            $student = Student::find($studentId);

            //if deleted, restore deleted member
            $this->restoreDeletedMember($student);

            $member = Member::updateOrCreate(
                [
                    'school_id' => $this->schoolId,
                    'ensemble_id' => $this->ensembleId,
                    'school_year' => $this->schoolYear,
                    'student_id' => $studentId,
                ],
                [
                    'voice_part_id' => $student->voice_part_id,
                    'office' => 'member',
                    'status' => 'active',
                ]
            );

        }

        //reset array
        $this->newMembers = [];
    }

    private function restoreDeletedMember(Student $student)
    {
        $member = Member::query()
            ->where('student_id', $student->id)
            ->where('ensemble_id', $this->ensembleId)
            ->where('school_year', $this->schoolYear)
            ->withTrashed()
            ->first();

        if ($member->trashed()) {
            $member->restore();
        }
    }
}
