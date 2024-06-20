<?php

namespace App\Livewire\Forms;

use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Members\Member;
use App\Models\Schools\School;
use App\Models\Students\Student;
use App\Models\User;
use App\Services\SplitNameIntoNamePartsService;
use Illuminate\Support\Facades\Hash;
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
    public string $office = 'member';
    public int $pronounId = 1;
    public int $schoolId = 0;
    public int $schoolYear;
    public int $srYear = 1960;
    public string $status = 'active';
    public array $statuses = [];
    public int $studentId = 0;
    public string $sysId = 'new';
    public int $voicePartId = 1;
    public array $voiceParts = [];

    protected function rules()
    {
        return [
            'classOfGrade' => ['required', 'int'],
            'ensembleId' => ['required', 'int', 'exists:ensembles,id'],
            'name' => ['required', 'string'],
            'office' => ['required', 'string'],
            'pronounId' => ['required', 'int', 'exists:pronouns,id'],
            'schoolId' => ['required', 'int', 'exists:schools,id'],
            'schoolYear' => ['required', 'int', 'min:1960', 'max:2054'],
            'status' => ['required', 'string'],
            'voicePartId' => ['required', 'int', 'exists:voice_parts,id'],
        ];
    }

    public function setStudentAsMember(Student $student): void
    {
        $this->name = $student->user->name;
        $this->classOfGrade = $student->class_of;
        $this->voicePartId = $student->voice_part_id;
        $this->schoolId = $student->schools->last()->id;
        $this->studentId = $student->id;
    }

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

        $student = $this->makeStudent($user);

        if ($student->id) {

            $this->studentId = $student->id;

            $member = Member::create(
                [
                    'school_id' => $this->schoolId,
                    'ensemble_id' => $this->ensembleId,
                    'school_year' => $this->schoolYear,
                    'student_id' => $this->studentId,
                    'voice_part_id' => $this->voicePartId,
                    'office' => $this->office,
                    'status' => $this->status,
                ]
            );
        } else {

            dd($this);
        }
    }

    private function makeUser(): User
    {
        //early exit
        if ($this->studentId) {
            return User::find($this->studentId);
        }

        //create fake email address
        $fakeEmail = uniqid().'@example.com';

        $service = new SplitNameIntoNamePartsService($this->name);
        $parts = $service->getNameParts();

        return User::create(
            [
                'name' => $this->name,
                'email' => $fakeEmail,
                'first_name' => $parts['first_name'],
                'middle_name' => $parts['middle_name'],
                'last_name' => $parts['last_name'],
                'suffix_name' => $parts['suffix_name'],
                'pronoun_id' => $this->pronounId,
                'password' => Hash::make($fakeEmail),
            ]
        );
    }

    private function makeStudent(User $user): Student
    {
        //early exit
        if ($this->studentId) {
            return Student::find($this->studentId);
        }

        if ($user->id) {

            //accepts default values for height, birthday, and shirt size
            return Student::create(
                [
                    'user_id' => $user->id,
                    'voice_part_id' => $this->voicePartId,
                    'class_of' => $this->classOf,
                ]
            );
        }
        return new Student();
    }

    private function updateSchoolEnsembleMember()
    {

    }

}
