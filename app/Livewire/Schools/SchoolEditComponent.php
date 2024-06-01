<?php

namespace App\Livewire\Schools;

use App\Livewire\BasePage;
use App\Livewire\Forms\SchoolForm;
use App\Models\County;
use App\Models\Schools\School;
use App\Models\Schools\SchoolTeacher;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Form;

class SchoolEditComponent extends BasePage
{
    public bool $emailVerified = false;
    public SchoolForm $form;
    public School $school;

    public function mount(): void
    {
        parent::mount();
        $this->school = School::find($this->dto['id']);
        $this->form->setSchool($this->school);
        $this->setEmailVerified();
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function setEmailVerified(): void
    {
        $this->emailVerified = (bool) SchoolTeacher::query()
            ->where('school_id', $this->school->id)
            ->where('teacher_id', auth()->id())
            ->first()
            ->email_verified_at;
    }

    public function render()
    {
        return view('livewire..schools.school-edit-component',
            [
                'counties' => County::orderBy('name')->pluck('name', 'id')->toArray(),
            ]);
    }

    public function save()
    {
        $this->validate();

        $this->form->update();

        $this->successMessage = '"'.$this->form->name.'" information has been updated.';

        $this->showSuccessIndicator = true;

        return redirect()->route('schools')->with($this->successMessage);
    }
}
