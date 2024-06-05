<?php

namespace App\Livewire\Students;

use App\Livewire\BasePage;
use App\Livewire\Forms\StudentForm;
use App\Models\Pronoun;
use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use App\Models\Students\VoicePart;
use App\Services\CalcClassOfFromGradeService;
use App\Services\CalcGradeFromClassOfService;
use Carbon\Carbon;
use Illuminate\Support\Number;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Form;

class StudentCreateComponent extends BasePage
{
    public string $hintBirthday = '';
    public string $hintClassOf = '';
    public StudentForm $form;
    public School $school;
    public string $schoolName;

    public function mount(): void
    {
        parent::mount();

        $this->form->setBirthday();
        $this->form->setStudent();

        $this->hintBirthday = Carbon::parse($this->form->birthday)->age.' years old.';

        $this->school = (auth()->user()->teacher->schools->count() === 1)
            ? auth()->user()->teacher->schools->first()
            : new School();

        if ($this->school->id) {
            $this->schoolName = $this->school->name;
            $this->hintClassOf = $this->setHintClassOf();
            $this->form->setSchool($this->school);
        }
    }

    public function render()
    {
        return view('livewire..students.student-create-component',
            [
                'grades' => $this->getGradesITeach(),
                'heights' => $this->getHeights(),
                'pronouns' => $this->getPronouns(),
                'schools' => $this->getSchools(),
                'shirtSizes' => $this->getShirtSizes(),
                'voiceParts' => $this->getVoiceParts(),
            ]);
    }

    public function updatedFormClassOf(): void
    {
        $service = new CalcGradeFromClassOfService();
        $this->hintClassOf = 'class of '.$this->form->classOf;
    }

    #[NoReturn] public function formCancel(): void
    {
        $this->form->resetDuplicateStudentAdvisory();
    }

    #[NoReturn] public function formContinue(): void
    {
        $this->form->updateWithoutDuplicateStudentCheck();
    }

    public function updatedFormBirthday(): void
    {
        $this->hintBirthday = Carbon::parse($this->form->birthday)->age.' years old';
    }

    public function save()
    {
        return ($this->form->update())
            ? redirect()->route('students')
            : redirect()->back();
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function getGradesITeach(): array
    {
        $a = [];

        $grades = ($this->school->id)
            ? GradesITeach::query()
                ->where('teacher_id', auth()->id())
                ->where('school_id', $this->school->id)
                ->orderBy('grade')
                ->pluck('grade')
                ->toArray()
            : [];

        if (count($grades)) {

            $service = new CalcClassOfFromGradeService();

            foreach ($grades as $grade) {

                $a[$service->getClassOf($grade)] = $grade;
            }
        }

        return $a;
    }

    private function getHeights(): array
    {
        $a = [];

        for ($i = 30; $i < 81; $i++) {

            $a[$i] = $i.' ('.floor($i / 12)."'".($i % 12).'")';
        }

        return $a;
    }

    private function getPronouns(): array
    {
        return Pronoun::all()
            ->pluck('descr', 'id')
            ->toArray();
    }

    private function getSchools(): array
    {
        return auth()->user()->teacher->schools
            ->pluck('name', 'id')
            ->toArray();
    }

    private function getShirtSizes(): array
    {
        return [
            '2xs', 'xs', 's', 'm', 'l', 'xl', '2xl', '3xl'
        ];
    }

    private function getVoiceParts(): array
    {
        return VoicePart::orderBy('order_by')
            ->pluck('descr', 'id')
            ->toArray();
    }

    private function setHintClassOf(): string
    {
        $service = new CalcClassOfFromGradeService();

        return 'class of '.array_key_first($this->getGradesITeach());
    }

}
