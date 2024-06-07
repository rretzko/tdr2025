<?php

namespace App\Livewire\Students;

use App\Livewire\BasePage;
use App\Livewire\Forms\StudentForm;
use App\Models\Students\Student;
use Carbon\Carbon;
use JetBrains\PhpStorm\NoReturn;

/**
 * Use for editing the student's "bio" tab
 */
class StudentEditComponent extends BasePageStudent
{
    public string $successMessagePronoun;
    public string $successMessageSchool;

    public function render()
    {
        return view('livewire..students.student-edit-component',
            [
                'grades' => $this->getGradesITeach(),
                'heights' => $this->getHeights(),
                'pronouns' => $this->getPronouns(),
                'schools' => $this->getSchools(),
                'shirtSizes' => $this->getShirtSizes(),
                'voiceParts' => $this->getVoiceParts(),
            ]

        );
    }

    #[NoReturn] public function updatedFormClassOf(): void
    {
        $this->student->update(['class_of' => $this->form->classOf]);

        $this->successMessageSchool = 'Grade updated.';
    }

    #[NoReturn] public function updatedFormBirthday(): void
    {
        $this->student->update(['birthday' => Carbon::parse($this->form->birthday)->format('Y-m-d')]);

        $this->successMessageSchool = 'Birthday updated.';
    }

    #[NoReturn] public function updatedFormFirst(): void
    {
        $this->student->user->update(['first_name' => $this->form->first]);

        $this->setUserName();

        $this->successMessage = 'First name updated.';
    }

    #[NoReturn] public function updatedFormHeightInInches(): void
    {
        $this->student->update(['height' => $this->form->heightInInches]);

        $this->successMessageSchool = 'Height updated.';
    }

    #[NoReturn] public function updatedFormLast(): void
    {
        $this->student->user->update(['last' => $this->form->last]);

        $this->setUserName();

        $this->successMessage = 'Last name updated.';
    }

    #[NoReturn] public function updatedFormMiddle(): void
    {
        $this->student->user->update(['middle_name' => $this->form->middle]);

        $this->setUserName();

        $this->successMessage = 'Middle name updated.';
    }

    #[NoReturn] public function updatedFormPronounId(): void
    {
        $this->student->user->update(['pronoun_id' => $this->form->pronounId]);

        $this->successMessagePronoun = 'Preferred pronoun updated.';
    }

    #[NoReturn] public function updatedFormShirtSize(): void
    {
        $this->student->update(['shirt_size' => $this->form->shirtSize]);

        $this->successMessageSchool = 'Shirt size updated.';
    }

    #[NoReturn] public function updatedFormSuffix(): void
    {
        $this->student->user->update(['last' => $this->form->suffix]);

        $this->setUserName();

        $this->successMessage = 'Suffix updated.';
    }

    #[NoReturn] public function updatedFormVoicePartId(): void
    {
        $this->student->update(['voice_part_id' => $this->form->voicePartId]);

        $this->successMessageSchool = 'Voice part updated.';
    }

}
