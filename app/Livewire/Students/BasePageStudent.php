<?php

namespace App\Livewire\Students;

use App\Livewire\BasePage;
use App\Livewire\Filters;
use App\Livewire\Forms\StudentForm;
use App\Models\Geostate;
use App\Models\Pronoun;
use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use App\Models\SchoolStudent;
use App\Models\Students\Student;
use App\Models\Students\VoicePart;
use App\Services\CalcClassOfFromGradeService;
use Carbon\Carbon;

/**
 * Use for editing the student's "bio" tab
 */
class BasePageStudent extends BasePage
{
    protected const TABS = ['bio', 'comms', 'emergency contact', 'reset password'];

    public Filters $filters;
    public StudentForm $form;
    public string $fullName;
    public string $hintClassOf;
    public string $hintBirthday;
    public string $selectedTab = 'bio';
    public Student $student;
    public string $sysId = 'new';
    public array $tabs = [];

    public function mount(): void
    {
        parent::mount();

        if ($this->school->id) {
            $this->hintClassOf = $this->setHintClassOf();
            $this->form->setSchool($this->school);
        }

        if ($this->dto['id']) {

            //set selected student
            //$this->student = Student::find(SchoolStudent::find($this->dto['id'])->student_id);
            $this->student = Student::find($this->dto['id']);

            $this->form->setStudent($this->getGradesITeach(), $this->student);
            $this->fullName = $this->student->user->name;
            $this->sysId = $this->student->id;

        } else {

            //set default student
            $this->form->setStudent($this->getGradesITeach());

        }

        $this->form->setBirthday();
        $this->form->setShirtSizes(self::SHIRTSIZES);

        $this->hintBirthday = Carbon::parse($this->form->birthday)->age.' years old.';

        $this->tabs = self::TABS;
    }

    protected function setHintClassOf(): string
    {
        return 'class of '.array_key_first($this->getGradesITeach());
    }

    protected function getGradesITeach(): array
    {
        $a = [];

        $grades = ($this->school->id)
            ? GradesITeach::query()
                ->where('teacher_id', auth()->id())
                ->where('school_id', $this->school->id)
                ->orderBy('grade')
                ->pluck('grade')
                ->toArray()
            : [9]; //default value

        if (count($grades)) {

            $service = new CalcClassOfFromGradeService();

            foreach ($grades as $grade) {

                //ex [2028 => 9, 2027 => 10, 2026 => 11, 2025 => 12]
                $a[$service->getClassOf($grade)] = $grade;
            }
        }

        return $a;
    }

    public function updatedSelectedTab(string $value)
    {
        $url = match ($value) {
            //'bio' => default
            'comms' => 'student/comms/edit/',
            'emergency contact' => 'student/ec/edit/',
            'reset password' => 'student/reset/',
            default => 'student/edit/',
        };

        return redirect($url.$this->student->id);
    }

    protected function getGeostates(): array
    {
        return Geostate::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    protected function getHeights(): array
    {
        $a = [];

        for ($i = 30; $i < 81; $i++) {

            $a[$i] = $i.' ('.floor($i / 12)."'".($i % 12).'")';
        }

        return $a;
    }

    protected function getPronouns(): array
    {
        return Pronoun::all()
            ->pluck('descr', 'id')
            ->toArray();
    }

    public function getSchools(): array
    {
        return auth()->user()->teacher->schools
            ->pluck('name', 'id')
            ->toArray();
    }

    protected function getShirtSizes(): array
    {
        return self::SHIRTSIZES;
    }

    protected function getVoiceParts(): array
    {
        return VoicePart::orderBy('order_by')
            ->pluck('descr', 'id')
            ->toArray();
    }

    protected function setUserName(): void
    {
        $this->student->user->updateName();

        $this->fullName = $this->student->user->name;
    }
}
