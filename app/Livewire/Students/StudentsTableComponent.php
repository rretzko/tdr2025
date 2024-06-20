<?php

namespace App\Livewire\Students;

use App\Livewire\BasePage;
use App\Models\Students\Student;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentsTableComponent extends BasePage
{
    public function mount(): void
    {
        parent::mount();
        $this->hasFilters = true;
        $this->hasSearch = true;
        $this->sortCol = 'users.last_name';
        $this->sortColLabel = 'name';
    }

    public function render()
    {
        return view('livewire..students.students-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows(),
            ]);
    }

    public function sortBy(string $key): void
    {
        $this->sortColLabel = $key;

        $properties = [
            'name' => 'users.last_name',
            'classOf' => 'students.class_of',
            'voicePart' => 'voice_parts.order_by',
        ];

        $requestedSort = $properties[$key];

        //toggle $this->sortAsc if user clicks on the same column header twice
        if ($requestedSort === $this->sortCol) {

            $this->sortAsc = (!$this->sortAsc);
        }

        $this->sortCol = $properties[$key];

    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    protected function applySearch($query)
    {
        return ($this->search === '')
            ? $query
            : $query->where('users.name', 'LIKE', '%'.$this->search.'%');
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => ''],
            ['label' => 'name', 'sortBy' => 'name'],
            ['label' => 'class of', 'sortBy' => 'classOf'],
            ['label' => 'voice part', 'sortBy' => 'voicePart'],
            ['label' => 'height', 'sortBy' => ''],
            ['label' => 'birthday', 'sortBy' => ''],
            ['label' => 'shirt size', 'sortBy' => ''],
        ];
    }

    private function getHeightVo(int $inches): string
    {
        return $inches.' ('.floor($inches / 12)."' ".($inches % 12).'")';
    }

    private function getRows(): LengthAwarePaginator
    {
        $a = [];

        return Student::query()
            ->join('school_student', 'students.id', '=', 'school_student.student_id')
            ->join('student_teacher', 'students.id', '=', 'student_teacher.student_id')
            ->join('schools', 'school_student.school_id', '=', 'schools.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('voice_parts', 'students.voice_part_id', '=', 'voice_parts.id')
            ->leftJoin('phone_numbers AS mobile', function ($join) {
                $join->on('users.id', '=', 'mobile.user_id')
                    ->where('mobile.phone_type', '=', 'mobile');
            })
            ->leftJoin('phone_numbers AS home', function ($join) {
                $join->on('users.id', '=', 'home.user_id')
                    ->where('home.phone_type', '=', 'home');
            })
            ->where('student_teacher.teacher_id', auth()->user()->teacher->id)
            ->where('users.name', 'LIKE', '%'.$this->search.'%')
            ->tap(function ($query) {
                $this->filters->filterStudentsBySchools($query);
            })
            ->select('users.name', 'schools.name AS schoolName', 'students.class_of AS classOf',
                'students.height', 'students.birthday', 'students.shirt_size AS shirtSize', 'students.id AS studentId',
                'voice_parts.descr AS voicePart', 'users.email', 'mobile.phone_number AS phoneMobile',
                'home.phone_number AS phoneHome', 'users.last_name', 'users.first_name', 'users.middle_name',
                'users.prefix_name', 'users.suffix_name'
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->paginate($this->recordsPerPage);
    }
}
