<?php

namespace App\Livewire\Students;

use App\Livewire\Forms\StudentForm;
use App\Models\Pronoun;
use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use App\Models\Students\VoicePart;
use App\Services\CalcClassOfFromGradeService;
use App\Services\CalcGradeFromClassOfService;
use Carbon\Carbon;
use JetBrains\PhpStorm\NoReturn;

class StudentCreateComponent extends BasePageStudent
{
    public function mount(): void
    {
        parent::mount();
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

    #[NoReturn] public function formCancel(): void
    {
        $this->form->resetDuplicateStudentAdvisory();
    }

    #[NoReturn] public function formContinue(): void
    {
        $this->form->updateWithoutDuplicateStudentCheck();
    }

    public function updatedFormClassOf(): void
    {
        $service = new CalcGradeFromClassOfService();
        $this->hintClassOf = 'class of '.$this->form->classOf;
    }

    public function updatedFormSchoolId(): void
    {
        $this->school = School::find($this->form->schoolId);
        $this->form->setSchool($this->school);
        $this->hintClassOf = $this->setHintClassOf();
        $this->form->setSchool($this->school);
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

}
