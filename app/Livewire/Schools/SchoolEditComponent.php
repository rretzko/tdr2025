<?php

namespace App\Livewire\Schools;

use App\Livewire\BasePage;
use App\Livewire\Forms\SchoolForm;
use App\Models\County;
use App\Models\Schools\School;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;
use App\Models\Schools\Teachers\Coteacher;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Form;

class SchoolEditComponent extends BasePage
{
    public array $coteacherIds = [];
    public bool $hasCoteachers = false;
    public bool $emailVerified = false;
    public SchoolForm $form;
    public School $school;

    public function mount(): void
    {
        parent::mount();
        $this->school = School::find($this->dto['id']);
        $this->form->setSchool($this->school);
        $this->setEmailVerified();
        $this->coteacherIds = $this->getCoteacherIds();
    }

    public function render()
    {
        //refresh
        $this->coteacherIds = $this->getCoteacherIds();

        return view('livewire..schools.school-edit-component',
            [
                'counties' => County::orderBy('name')->pluck('name', 'id')->toArray(),
                'schoolTeachers' => $this->getSchoolTeachers(),
                'subjects' => ['chorus', 'band', 'orchestra'],
            ]);
    }

    public function updatedCoteacherIds(): void
    {
        //delete any current rows
        $myTeacherId = Teacher::where('user_id', auth()->id())->first()->id;
        Coteacher::query()
            ->where('teacher_id', $myTeacherId)
            ->where('school_id', $this->school->id)
            ->delete();

        //insert new rows
        foreach ($this->coteacherIds as $coteacherId) {

            Coteacher::create(
                [
                    'teacher_id' => $myTeacherId,
                    'school_id' => $this->school->id,
                    'coteacher_id' => $coteacherId,
                ]
            );
        }

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

    private function getCoteacherIds(): array
    {
        return Coteacher::where('teacher_id', auth()->id())->pluck('coteacher_id')->toArray();
    }

    private function getSchoolTeachers(): array
    {
        $myTeacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $teachers = [];

        foreach ($this->school->activeTeachers as $teacher) {

            if ($teacher->id != $myTeacherId) {
                $teachers[] = ['id' => $teacher->id, 'name' => $teacher->user->name];
            }
        }

        $this->hasCoteachers = (bool) count($teachers);

        return $teachers;
    }

    public function save()
    {
        $this->validate();

        $this->form->update();

        $this->successMessage = '"'.$this->form->name.'" information has been updated.';

        $this->showSuccessIndicator = true;

        return redirect()->route('schools')->with($this->successMessage);
    }

    #[NoReturn] public function updatedFormEmail(): void
    {
        $this->form->updatedEmail();
    }
}
