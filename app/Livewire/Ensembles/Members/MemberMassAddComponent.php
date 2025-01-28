<?php

namespace App\Livewire\Ensembles\Members;

use App\Livewire\BasePage;
use App\Livewire\Forms\EnsembleMassAddForm;
use App\Models\Ensembles\Ensemble;
use App\Models\Students\Student;
use App\Models\UserConfig;
use App\Services\CalcClassOfFromGradeService;
use App\Services\CalcSeniorYearService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemberMassAddComponent extends BasePage
{
    public EnsembleMassAddForm $form;
    public array $ensembleClassOfs = [];
    public array $ensembles = [];
    public int $schoolId = 0;
    public array $schoolYears = [];
    public int $srYear = 0;
    public array $students = [];

    public function mount(): void
    {
        parent::mount();

        $this->schoolId = UserConfig::getValue('schoolId');

        //set default value in $form
        if (!$this->form->schoolId) {
            $this->form->schoolId = $this->schoolId;
        }

        $this->ensembles = $this->getEnsemblesArray($this->schoolId);

        //set default value in $form
        if (!$this->form->ensembleId) {
            $this->form->ensembleId = array_key_first($this->ensembles);
        }

        $this->form->ensembleName = Ensemble::find($this->form->ensembleId)->name;

        $this->schoolYears = $this->getSchoolYearsArray($this->schoolId);

        if (!$this->srYear) {
            $srYearService = new CalcSeniorYearService();
            $this->srYear = $srYearService->getSeniorYear();
        }

        //set default value in $form
        if (!$this->form->schoolYear) {
            $this->form->schoolYear = $this->srYear;
        }

        $this->ensembleClassOfs = $this->getEnsembleClassOfsArray($this->form->ensembleId, $this->srYear);

        $this->students = $this->getStudentsArray($this->schoolId, $this->ensembleClassOfs, $this->srYear);
    }

    public function render()
    {
        return view('livewire..ensembles.members.member-mass-add-component');
    }

    public function save(): void
    {
        $this->form->save();

        $this->students = $this->getStudentsArray($this->schoolId, $this->ensembleClassOfs, $this->srYear);
    }

    public function updatedFormEnsembleId(): void
    {
        $this->form->ensembleName = Ensemble::find($this->form->ensembleId)->name;
        $ensembleClassOfs = $this->getEnsembleClassOfsArray($this->form->ensembleId, $this->srYear);
        $this->students = $this->getStudentsArray($this->form->schoolId, $ensembleClassOfs, $this->form->schoolYear);
    }

    public function updatedSrYear(): void
    {
        $this->ensembleClassOfs = $this->getEnsembleClassOfsArray($this->form->ensembleId, $this->srYear);
        $this->students = $this->getStudentsArray($this->schoolId, $this->ensembleClassOfs, $this->srYear);
        $this->form->schoolYear = $this->srYear;
    }

    /** END OF PUBLIC FUNCTIONS ******************************************************************/

    private function getEnsemblesArray(int $schoolId): array
    {
        return Ensemble::query()
            ->where('school_id', $schoolId)
            ->pluck('name', 'id')
            ->toArray();
    }

    private function getSchoolYearsArray(int $schoolId): array
    {
        return Student::query()
            ->join('school_student', 'students.id', '=', 'school_student.student_id')
            ->where('school_student.school_id', $schoolId)
            ->select('students.class_of')
            ->distinct()
            ->orderByDesc('students.class_of')
            ->pluck('students.class_of', 'students.class_of')
            ->toArray();
    }

    private function getEnsembleClassOfsArray(int $ensembleId, int $srYear): array
    {
        $classOfs = [];
        $grades = explode(',', Ensemble::find($ensembleId)->grades);
        $service = new CalcClassOfFromGradeService();

        foreach ($grades as $grade) {
            $classOfs[] = $service->getClassOf($grade, $srYear);
        }

        return $classOfs;
    }

    private function getStudentsArray(int $schoolId, array $classOfs, $srYear): array
    {
        $ensembleMemberStudentIds = Ensemble::find($this->form->ensembleId)
            ->activeMemberStudentIdsArray($this->srYear);

        return Student::query()
            ->join('school_student', 'students.id', '=', 'school_student.student_id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('school_student.school_id', $schoolId)
            ->whereIn('students.class_of', $classOfs)
            ->whereNotIn('students.id', $ensembleMemberStudentIds)
            ->select('students.id', 'students.class_of',
                'users.last_name', 'users.first_name', 'users.middle_name')
            ->selectRaw('? AS srYear', [$srYear])
            ->selectRaw('(students.class_of - ?) AS delta', [$srYear])
            ->selectRaw('(12 - (students.class_of - ?)) AS calcGrade', [$srYear])
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get()
            ->toArray();
    }
}
