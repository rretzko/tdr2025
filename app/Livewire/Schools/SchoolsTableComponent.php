<?php

namespace App\Livewire\Schools;

use App\Models\Schools\GradesITeach;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class SchoolsTableComponent extends Component
{
    public array $dto;
    public Collection $schools;
    public Teacher $teacher;

    public function mount()
    {
        $this->teacher = Teacher::find(auth()->id());
        $this->schools = $this->teacher->schools->sortBy('name');
    }

    public function render()
    {
        return view('livewire.schools.schools-table-component',
            [
                'columnHeaders' => ['name', 'address', 'grades', 'active?', 'email', 'verified', 'i teach',],
                'rows' => $this->rows(),
            ]);
    }

    private function rows(): array
    {
        $a = [];

        foreach ($this->schools as $key => $school) {

            $schoolTeacher = SchoolTeacher::query()
                ->where('school_id', $school->id)
                ->where('teacher_id', auth()->id())
                ->first();

            $gradesITeach = GradesITeach::query()
                ->where('school_id', $school->id)
                ->where('teacher_id', auth()->id())
                ->pluck('grade')
                ->toArray();

            $a[$key] = [
                $school->id,
                $school->name,
                $school->address,
                (!is_null($school->grades)) ? implode(', ', $school->grades) : 'none',
                $schoolTeacher->active
                    ? $this->checkBadge()
                    : $this->thumbsDown(),     // 'flex justify-center items-center']),
                $schoolTeacher->email,
                (is_null($schoolTeacher->email_verified_at)
                    ? $this->thumbsDown() //, 'flex justify-center items-center'
                    : $this->checkBadge()), //'flex justify-center items-center']),
                (!empty($gradesITeach) ? implode(', ', $gradesITeach) : 'none'),
            ];
        }

        return $a;
    }

//    private function getRows(): array
//    {
//        $rows = [];
//
//        foreach($this->schools AS $school){
//
//            $rows[] =  $this->rowsSchools();
//        }
//
//        return $rows;
//    }

    /** HEROICONS ****************************************************************/

    private function checkBadge(): string
    {
        return '<span class="text-green-600">
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
     class="w-6 h-6">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
</svg>
</span>';

    }

    private function thumbsDown(): string
    {
        return '<span class="text-red-500">
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
    <path stroke-linecap="round" stroke-linejoin="round" d="M7.498 15.25H4.372c-1.026 0-1.945-.694-2.054-1.715a12.137 12.137 0 0 1-.068-1.285c0-2.848.992-5.464 2.649-7.521C5.287 4.247 5.886 4 6.504 4h4.016a4.5 4.5 0 0 1 1.423.23l3.114 1.04a4.5 4.5 0 0 0 1.423.23h1.294M7.498 15.25c.618 0 .991.724.725 1.282A7.471 7.471 0 0 0 7.5 19.75 2.25 2.25 0 0 0 9.75 22a.75.75 0 0 0 .75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 0 0 2.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384m-10.253 1.5H9.7m8.075-9.75c.01.05.027.1.05.148.593 1.2.925 2.55.925 3.977 0 1.487-.36 2.89-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398-.306.774-1.086 1.227-1.918 1.227h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 0 0 .303-.54" />
</svg></span>';
    }

    public function toggleActive(int $schoolId)
    {
        $schoolTeacher = SchoolTeacher::query()
            ->where('school_id', $schoolId)
            ->where('teacher_id', $this->teacher->id)
            ->first();

        $schoolTeacher->update(['active' => $schoolTeacher->active ? 0 : 1]);
    }

}
