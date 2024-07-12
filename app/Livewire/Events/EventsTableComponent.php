<?php

namespace App\Livewire\Events;

use App\Livewire\BasePage;
use App\Models\Events\Event;
use Illuminate\Database\Eloquent\Builder;

class EventsTableComponent extends BasePage
{
    public function mount(): void
    {
        parent::mount();

        $this->sortCol = 'events.name';
    }

    public function render()
    {
        $this->saveSortParameters();

        return view('livewire..events.events-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
            ]);
    }

    /** END OF PUBLIC FUNCtiONS **************************************************/

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'name/short name/org', 'sortBy' => 'name'], //users.last_name
            ['label' => 'grades', 'sortBy' => null],
            ['label' => 'ensembles', 'sortBy' => null],
            ['label' => 'status', 'sortBy' => null],
            ['label' => 'versions', 'sortBy' => null],
        ];
    }

    private function getRows(): Builder
    {
        return Event::query()
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'));

//        return Student::query()
//            ->join('school_student', 'students.id', '=', 'school_student.student_id')
//            ->join('student_teacher', 'students.id', '=', 'student_teacher.student_id')
//            ->join('schools', 'school_student.school_id', '=', 'schools.id')
//            ->join('users', 'students.user_id', '=', 'users.id')
//            ->join('voice_parts', 'students.voice_part_id', '=', 'voice_parts.id')
//            ->leftJoin('phone_numbers AS mobile', function ($join) {
//                $join->on('users.id', '=', 'mobile.user_id')
//                    ->where('mobile.phone_type', '=', 'mobile');
//            })
//            ->leftJoin('phone_numbers AS home', function ($join) {
//                $join->on('users.id', '=', 'home.user_id')
//                    ->where('home.phone_type', '=', 'home');
//            })
//            ->where('student_teacher.teacher_id', auth()->user()->teacher->id)
//            ->where('users.name', 'LIKE', '%'.$this->search.'%')
//            ->tap(function ($query) {
//                $this->filters->filterStudentsBySchools($query);
//                $this->filters->filterStudentsByClassOfs($query, $this->search);
//                $this->filters->filterStudentsByVoicePartIds($query, $this->search);
//            })
//            ->select('users.name',
//                'schools.name AS schoolName', 'schools.id AS schoolId',
//                'school_student.id AS schoolStudentId', 'school_student.active',
//                'students.class_of AS classOf', 'students.height', 'students.birthday',
//                'students.shirt_size AS shirtSize', 'students.id AS studentId',
//                'voice_parts.descr AS voicePart', 'users.email', 'mobile.phone_number AS phoneMobile',
//                'home.phone_number AS phoneHome', 'users.last_name', 'users.first_name', 'users.middle_name',
//                'users.prefix_name', 'users.suffix_name'
//            )
//            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'));

    }

    public function sortBy(string $key): void
    {
        $this->sortColLabel = $key;

        $properties = [
            'name' => 'events.name',
        ];

        $requestedSort = $properties[$key];

        //toggle $this->sortAsc if user clicks on the same column header twice
        if ($requestedSort === $this->sortCol) {

            $this->sortAsc = (!$this->sortAsc);
        }

        $this->sortCol = $properties[$key];

    }
}
