<?php

namespace App\Livewire\Schools;

use App\Livewire\BasePage;
use App\Models\PageInstruction;
use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;
use App\Services\SchoolsTableService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Redirector;
use Livewire\Component;

class SchoolsTableComponent extends BasePage
{
    public int $schoolCount = 0;
    public Collection $schools;
    public Teacher $teacher;

    public function mount(): void
    {
        parent::mount();
        $this->teacher = Teacher::find(auth()->id());
        $this->schools = $this->teacher->schools->sortBy('name');
        $this->schoolCount = $this->schools->count();
    }

    public function render()
    {
        return view('livewire.schools.schools-table-component',
            [
                'columnHeaders' => ['name', 'address', 'grades', 'active?', 'email', 'verified', 'i teach',],
                'rows' => $this->rows(),
            ]);
    }

    public function edit(int $schoolId)
    {
        return $this->redirectRoute('school.edit', ['school' => $schoolId]);
    }

    public function remove(int $schoolId): void
    {
        $schoolTeacher = SchoolTeacher::query()
            ->where('school_id', $schoolId)
            ->where('teacher_id', auth()->id())
            ->first();

        $schoolName = $schoolTeacher->schoolName;

        $schoolTeacher->delete();

        $this->showSuccessIndicator = true;
        $this->successMessage = '"'.$schoolName.'" has been removed from your roster.';


    }

    public function toggleActive(int $schoolId)
    {
        $schoolTeacher = SchoolTeacher::query()
            ->where('school_id', $schoolId)
            ->where('teacher_id', $this->teacher->id)
            ->first();

        $schoolTeacher->update(['active' => $schoolTeacher->active ? 0 : 1]);
    }

    private function rows(): array
    {
        $a = [];

        $service = new SchoolsTableService();

        return $service->getTableRows();
    }


}
