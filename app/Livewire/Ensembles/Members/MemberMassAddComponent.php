<?php

namespace App\Livewire\Ensembles\Members;

use App\Livewire\BasePage;
use App\Livewire\Forms\EnsembleMassAddForm;
use App\Models\Ensembles\Ensemble;
use App\Models\Students\Student;
use App\Models\UserConfig;
use App\Services\CalcClassOfFromGradeService;
use App\Services\CalcSeniorYearService;

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

        $this->ensembles = $this->getEnsemblesArray($this->schoolId);

        if (!$this->form->ensembleId) {
            $this->form->ensembleId = array_key_first($this->ensembles);
        }

        $this->schoolYears = $this->getSchoolYearsArray($this->schoolId);

        if (!$this->srYear) {
            $srYearService = new CalcSeniorYearService();
            $this->srYear = $srYearService->getSeniorYear();
        }

        $this->ensembleClassOfs = $this->getEnsembleClassOfsArray($this->form->ensembleId, $this->srYear);

        $this->students = $this->getStudentsArray($this->schoolId, $this->ensembleClassOfs);

    }

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

    /** END OF PUBLIC FUNCTIONS ******************************************************************/

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

    private function getStudentsArray(int $schoolId, array $classOfs): array
    {
        return Student::query()
            ->join('school_student', 'students.id', '=', 'school_student.student_id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('school_student.school_id', $schoolId)
            ->whereIn('students.class_of', $classOfs)
            ->select('students.id', 'students.class_of',
                'users.last_name', 'users.first_name', 'users.middle_name')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire..ensembles.members.member-mass-add-component');
    }

    public function updatedSrYear(): void
    {
        $this->ensembleClassOfs = $this->getEnsembleClassOfsArray($this->form->ensembleId, $this->srYear);
        $this->students = $this->getStudentsArray($this->schoolId, $this->ensembleClassOfs);
    }
}
