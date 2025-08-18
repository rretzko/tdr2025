<?php

namespace App\Livewire\Schools;

use App\Livewire\BasePage;
use App\Models\PageInstruction;
use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;
use App\Models\Schools\Teachers\TeacherSubject;
use App\Services\SchoolsTableService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SchoolsTableComponent extends BasePage
{
    public int $schoolCount = 0;
    public Teacher $teacher;

    public function mount(): void
    {
        parent::mount();
        $this->teacher = Teacher::find(auth()->id());
        $this->schoolCount = count($this->schools);
    }

    public function render()
    {
        $this->refreshSchools();
        $this->schoolCount = count($this->schools);

        return view('livewire.schools.schools-table-component',
            [
                'columnHeaders' => ['name', 'address', 'grades', 'active?', 'email', 'verified', 'i teach', 'subjects'],
                'rows' => $this->rows(),
            ]);
    }

    public function deactivate(int $schoolId): void
    {
        $st = SchoolTeacher::query()
            ->where('school_id', $schoolId)
            ->where('teacher_id', $this->teacher->id)
            ->first();

        $active = $st->active;

        $st->update(['active' => !$active]);
    }

    public function edit(int $schoolId)
    {
        return $this->redirectRoute('school.edit', ['school' => $schoolId]);
    }

    /**
     * @todo Test to determine impact of leaving student_teacher relationship in-place while removing the school_teacher relationship
     */
    public function remove(int $schoolId): void
    {
        //remove teacherSubject
        TeacherSubject::query()
            ->where('school_id', $schoolId)
            ->where('teacher_id', auth()->id())
            ->delete();

        $schoolTeacher = SchoolTeacher::query()
            ->where('school_id', $schoolId)
            ->where('teacher_id', auth()->id())
            ->first();

        $schoolName = $schoolTeacher->schoolName;

        //remove schoolTeacher
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
