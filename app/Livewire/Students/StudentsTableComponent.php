<?php

namespace App\Livewire\Students;

use App\Livewire\BasePage;
use App\Models\Students\Student;
use App\Models\UserFilter;
use App\Models\UserSort;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;

class StudentsTableComponent extends BasePage
{
    public function mount(): void
    {
        parent::mount();

        $userSort = UserSort::query()
            ->where('user_id', auth()->id())
            ->where('header', $this->dto['header'])
            ->first();

        $this->hasFilters = true;
        $this->hasSearch = true;

        //filters
        $this->filters->schoolsSelectedIds = UserFilter::query()
            ->where('user_id', auth()->id())
            ->where('header', $this->dto['header'])
            ->where('filter', 'schoolsSelectedIds')
            ->exists()
            ? UserFilter::query()
                ->where('user_id', auth()->id())
                ->where('header', $this->dto['header'])
                ->where('filter', 'schoolsSelectedIds')
                ->pluck('values')
                ->toArray()
            : $this->filters->schoolsSelectedIds;

        $this->sortCol = $userSort ? $userSort->column : 'users.last_name';
        $this->sortAsc = $userSort ? $userSort->asc : $this->sortAsc;
        $this->sortColLabel = 'name';
    }

    public function render()
    {
        $this->saveSortParameters();
        $this->saveFilterParameters();

        $rows = $this->getRows();

        return view('livewire..students.students-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $rows,
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
            ['label' => 'name', 'sortBy' => 'name'], //users.last_name
            ['label' => 'class of', 'sortBy' => 'classOf'], //students.class_of
            ['label' => 'voice part', 'sortBy' => 'voicePart'], //voice_parts.order_by
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
        //$this->logSql();

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
                $this->filters->filterStudentsByClassOfs($query);
                $this->filters->filterStudentsByVoicePartIds($query);
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

    protected function saveFilterParameters(): void
    {
        //schoolsSelectedIds
        UserFilter::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'header' => $this->dto['header'],
                'filter' => 'schoolsSelectedIds'
            ],
            [
                'values' => json_encode($this->filters->schoolsSelectedIds),
            ]
        );

        //classOfsSelectedIds
        UserFilter::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'header' => $this->dto['header'],
                'filter' => 'classOfsSelectedIds'
            ],
            [
                'values' => json_encode($this->filters->classOfsSelectedIds),
            ]
        );

        //voicePartsSelectedIds
        UserFilter::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'header' => $this->dto['header'],
                'filter' => 'voicePartIdsSelectedIds'
            ],
            [
                'values' => json_encode($this->filters->voicePartIdsSelectedIds),
            ]
        );
    }

    protected function saveSortParameters(): void
    {
        UserSort::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'header' => $this->dto['header']
            ],
            [
                'column' => $this->sortCol,
                'asc' => $this->sortAsc,
            ]
        );
    }

    /**
     * for troubleshooting
     */
    private function logSql(): void
    {
        $sql = Student::query()
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
                $this->filters->filterStudentsByClassOfs($query);
                $this->filters->filterStudentsByVoicePartIds($query);
            })
            ->select('users.name', 'schools.name AS schoolName', 'students.class_of AS classOf',
                'students.height', 'students.birthday', 'students.shirt_size AS shirtSize', 'students.id AS studentId',
                'voice_parts.descr AS voicePart', 'users.email', 'mobile.phone_number AS phoneMobile',
                'home.phone_number AS phoneHome', 'users.last_name', 'users.first_name', 'users.middle_name',
                'users.prefix_name', 'users.suffix_name'
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->toRawSql();

        Log::info($sql);
    }
}
