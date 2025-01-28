<?php

namespace App\Livewire\Forms;

use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Inventories\Inventory;
use App\Models\Ensembles\Members\Member;
use App\Models\Schools\School;
use App\Models\Students\Student;
use App\Models\User;
use App\Services\CalcSeniorYearService;
use App\Services\SplitNameIntoNamePartsService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SchoolEnsembleMemberForm extends Form
{
    public array $memberAssets = [];
    public int $classOf = 1960;
    public int $classOfGrade;
    public Ensemble $ensemble;
    #[Validate('required|int')]
    public int $ensembleId = 0;
    public string $ensembleName = '';
    public int $grade;
    public string $name = '';
    public string $office = 'member';
    public int $pronounId = 1;
    public int $schoolId = 0;
    public string $schoolName = '';
    public int $schoolYear;
    public int $srYear = 1960;
    public string $status = 'active';
    public array $statuses = [];
    public int $studentId = 0;
    public string $sysId = 'new';
    public int $voicePartId = 1;
    public array $voiceParts = [];
    public int $userId = 0;

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

    public function getAssignedAssets(): array
    {
        $this->setAssignedAssets();

        return $this->memberAssets;
    }

    public function setAssignedAssets(): void
    {
        //clear artifacts
        $this->memberAssets = [];

        $inventories = Inventory::query()
            ->join('assets', 'assets.id', '=', 'inventories.asset_id')
            ->where('assigned_to', $this->userId)
            ->where('status', 'assigned')
            ->select('inventories.*', 'assets.name AS assetName')
            ->get();

//        Log::info(Inventory::query()
//            ->join('assets', 'assets.id', '=', 'inventories.asset_id')
//            ->where('assigned_to', $this->userId)
//            ->where('status', 'assigned')
//            ->select('inventories.*', 'assets.name AS assetName')
//            ->toRawSql());

        foreach ($inventories as $inventory) {

            $this->memberAssets[$inventory->assetName]['id'] = $inventory->id;
            $this->memberAssets[$inventory->assetName]['label'] = '#'.$inventory->item_id;

            $this->memberAssets[$inventory->assetName]['label'] .= (strlen($inventory->color))
                ? ', '.$inventory->color
                : '';

            $this->memberAssets[$inventory->assetName]['label'] .= (strlen($inventory->size))
                ? ', '.$inventory->size
                : '';
        }

    }

    public function setMember(int $id): void
    {
        $member = Member::find($id);
        $service = new CalcSeniorYearService();

        $this->ensembleId = $member->ensemble_id;
        $this->ensemble = Ensemble::find($member->ensemble_id);
        $this->ensembleName = $this->ensemble->name;
        $this->schoolId = $this->ensemble->school_id;
        $this->status = $member->status;
        $this->studentId = $member->student_id;
        $student = Student::find($this->studentId);
        $this->userId = $student->user->id;
        $this->name = $student->user->name;
        $this->sysId = $id;
        $this->classOf = $student->class_of;
        $this->classOfGrade = $student->class_of;
        $this->voicePartId = $member->voice_part_id;
        $this->pronounId = $student->user->pronoun_id;
        $this->srYear = $service->getSeniorYear();
        $this->schoolName = School::find($this->schoolId)->name;
        $this->schoolYear = $member->school_year;

        $this->setAssignedAssets();
    }

    public function setStudentAsMember(Student $student): void
    {
        $this->name = $student->user->name;
        $this->classOfGrade = $student->class_of;
        $this->voicePartId = $student->voice_part_id;
        $this->schoolId = $student->schools->last()->id;
        $this->studentId = $student->id;
    }

    public function update(): void
    {
        $this->validate();

        ($this->sysId === 'new')
            ? $this->add()
            : $this->updateSchoolEnsembleMember();
    }

    public function updateAndStay()
    {
        $this->update();

        $this->reset('name', 'office');
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

    private function updateSchoolEnsembleMember(): void
    {
        $member = Member::find($this->sysId);

        $member->update(
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
    }

}
