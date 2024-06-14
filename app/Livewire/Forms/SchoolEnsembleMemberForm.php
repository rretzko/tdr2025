<?php

namespace App\Livewire\Forms;

use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Members\Member;
use App\Models\Schools\School;
use App\Models\Students\Student;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SchoolEnsembleMemberForm extends Form
{
    public int $classOf = 1960;
    public int $classOfGrade;
    public Ensemble $ensemble;
    public int $ensembleId = 0;
    public int $grade;
    public string $name = '';
    public string $office = '';
    public int $schoolId = 0;
    public int $schoolYear;
    public string $status = '';
    public array $statuses = [];
    public int $srYear = 1960;
    public string $sysId = 'new';
    public int $voicePartId = 1;
    public array $voiceParts = [];

    public function update()
    {
        $this->validate();

        ($this->sysId === 'new')
            ? $this->add()
            : $this->updateSchoolEnsembleMember();
    }

    private function add()
    {
        $user = $this->makeUser();

        $student = $this->makeStudent();

        $member = Member::create(
            [
                'school_id' => $this->schoolId,
                'ensemble_id' => $this->ensembleId,
                'school_year' => $this->schoolYear,
                'student_id' => $student->id,
                'class_of' => $this->classOf,
                'voice_part_id' => $this->voicePartId,
                'office' => $this->office,
                'status' => $this->status,
            ]
        );
    }

    private function makeUser(): User
    {
        return new User();
    }

    private function makeStudent(): Student
    {
        return new Student();
    }

    private function updateSchoolEnsembleMember()
    {

    }

}
