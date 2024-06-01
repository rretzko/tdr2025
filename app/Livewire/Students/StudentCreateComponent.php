<?php

namespace App\Livewire\Students;

use App\Livewire\BasePage;
use App\Livewire\Forms\StudentForm;
use App\Models\Schools\GradesITeach;
use Livewire\Form;

class StudentCreateComponent extends BasePage
{
    public StudentForm $form;
    public int $schoolId = 0;

    public function render()
    {
        return view('livewire..students.student-create-component',
            [
                'grades' => $this->getGradesITeach(),
                'schools' => $this->getSchools(),
            ]);
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function getGradesITeach(): array
    {
        return ($this->schoolId)
            ? GradesITeach::query()
                ->where('teacher_id', auth()->id())
                ->where('school_id', $this->schoolId)
                ->orderBy('grade')
                ->pluck('grade')
                ->toArray()
            : [];
    }

    private function getSchools(): array
    {
        return auth()->user()->teacher->schools
            ->pluck('name', 'id')
            ->toArray();
    }

    public function save(): void
    {
        $this->form->update();
    }
}
