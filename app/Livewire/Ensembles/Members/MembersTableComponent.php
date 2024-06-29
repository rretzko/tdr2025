<?php

namespace App\Livewire\Ensembles\Members;

use App\Exports\SchoolEnsembleMembersExport;
use App\Models\UserSort;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class MembersTableComponent extends BasePageMember
{
    public function mount(): void
    {
        parent::mount();

        $this->hasFilters = true;
        $this->hasSearch = true;

        $this->setFilterMethods();

        //sorts
        $this->sortCol = $this->userSort ? $this->userSort->column : 'users.last_name';
        $this->sortAsc = $this->userSort ? $this->userSort->asc : $this->sortAsc;
        $this->sortColLabel = $this->userSort ? $this->userSort->label : 'name/school';
    }

    public function render()
    {
        $this->saveSortParameters();

        $this->filters->setFilter('schoolsSelectedIds', $this->dto['header']);
        $this->filters->setFilter('ensemblesSelectedIds', $this->dto['header']);
        $this->filters->setFilter('ensembleYearsSelectedIds', $this->dto['header']);

        return view('livewire..ensembles.members.members-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getMembers(),
                'tabs' => self::ENSEMBLETABS,
            ]);
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new SchoolEnsembleMembersExport, 'members.csv');
    }

    public function sortBy(string $key): void
    {
        $this->sortColLabel = $key;

        $properties = [
            'name/school' => 'users.last_name',
            'ensemble' => 'ensembles.id',
            'voicePart' => 'voice_parts.order_by',
            'classOf' => 'students.class_of',
            'schoolYear' => 'ensemble_members.school_year',
            'status' => 'ensemble_members.status',
            'office' => 'ensemble_members.office',
        ];

        $requestedSort = $properties[$key];

        //toggle $this->sortAsc if user clicks on the same column header twice
        if ($requestedSort === $this->sortCol) {

            $this->sortAsc = (!$this->sortAsc);
        }

        $this->sortCol = $properties[$key];

    }

    public function updatedSelectedTab()
    {
        $uri = ($this->selectedTab === 'ensembles')
            ? '/ensembles'
            : '/ensembles/'.$this->selectedTab;

        $this->redirect($uri);
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => ''],
            ['label' => 'name/school', 'sortBy' => 'name/school'],
            ['label' => 'ensemble', 'sortBy' => 'ensemble'],
            ['label' => 'voice part', 'sortBy' => 'voicePart'],
            ['label' => 'grade', 'sortBy' => 'classOf'],
            ['label' => 'year', 'sortBy' => 'schoolYear'],
            ['label' => 'status', 'sortBy' => 'status'],
            ['label' => 'office', 'sortBy' => 'office'],
        ];
    }

    private function getMembers(): array
    {
        $schoolIds = auth()->user()->teacher->schools->pluck('id')->toArray();

        //$this->logSql($schoolIds);

        return DB::table('ensemble_members')
            ->join('students', 'ensemble_members.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('school_student', 'ensemble_members.student_id', '=', 'school_student.student_id')
            ->join('ensembles', 'ensemble_members.ensemble_id', '=', 'ensembles.id')
            ->join('voice_parts', 'ensemble_members.voice_part_id', '=', 'voice_parts.id')
            ->join('schools', 'school_student.school_id', '=', 'schools.id')
            ->whereIn('ensemble_members.school_id', $schoolIds)
            ->tap(function ($query) {
                $this->filters->filterStudentsBySchools($query);
                $this->filters->filterMembersByEnsemble($query);
                $this->filters->filterMembersBySchoolYear($query);
            })
            ->select('users.name', 'schools.name AS schoolName', 'ensembles.name AS ensembleName',
                'voice_parts.descr AS voicePartDescr', 'students.class_of',
                'ensemble_members.school_year', 'ensemble_members.status', 'ensemble_members.office',
                'ensemble_members.id')
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->get()
            ->toArray();
    }

    /**
     * for troubleshooting
     */
    private function logSql(array $schoolIds): void
    {
        $sql = DB::table('ensemble_members')
            ->join('students', 'ensemble_members.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('school_student', 'ensemble_members.student_id', '=', 'school_student.student_id')
            ->join('ensembles', 'ensemble_members.ensemble_id', '=', 'ensembles.id')
            ->join('voice_parts', 'ensemble_members.voice_part_id', '=', 'voice_parts.id')
            ->join('schools', 'school_student.school_id', '=', 'schools.id')
            ->whereIn('ensemble_members.school_id', $schoolIds)
            ->tap(function ($query) {
                $this->filters->filterStudentsBySchools($query);
                $this->filters->filterMembersByEnsemble($query);
                $this->filters->filterMembersBySchoolYear($query);
            })
            ->select('users.name', 'schools.name AS schoolName', 'ensembles.name AS ensembleName',
                'voice_parts.descr AS voicePartDescr', 'students.class_of',
                'ensemble_members.school_year', 'ensemble_members.status', 'ensemble_members.office',
                'ensemble_members.id')
            ->toRawSql();

        Log::info($sql);
    }

    private function setFilterMethods(): void
    {
        if (count($this->schools) > 1) {

            $this->filterMethods[] = 'schools';
        }

        if (count($this->filters->ensembles()) > 1) {

            $this->filterMethods[] = 'ensembles';
        }

        if (count($this->filters->ensembleYears()) > 1) {

            $this->filterMethods[] = 'ensembleYears';
        }
    }

}
