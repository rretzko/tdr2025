<?php

namespace App\Livewire\Events\Versions\Reports;

use App\Exports\ParticipatingTeachersExport;
use App\Livewire\Events\Versions\Reports\BasePageReports;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ParticipatingTeachersComponent extends BasePageReports
{
    public array $columnHeaders = [];
    public array $schoolIds = [];
    public array $teacherIds = [];

    public function mount(): void
    {
        parent::mount();

        $this->columnHeaders = $this->getColumnHeaders();

        $this->sortCol = 'users.last_name';
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'teacher', 'sortBy' => 'name'], //users.last_name
            ['label' => 'school', 'sortBy' => 'school'],
            ['label' => 'registrant#', 'sortBy' => 'count'],
        ];
    }

    public function render()
    {
        return view('livewire..events.versions.reports.participating-teachers-component',
            [
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
            ]);
    }

    private function getRows(): \Illuminate\Database\Query\Builder
    {
        $search = $this->search;
        $teacherAndSchoolIds = $this->getParticipatingTeacherAndSchoolIds();
        $this->teacherIds = array_column($teacherAndSchoolIds, 'teacher_id');
        $this->schoolIds = array_column($teacherAndSchoolIds, 'school_id');

        return DB::table('school_teacher')
            ->join('teachers', 'teachers.id', '=', 'school_teacher.teacher_id')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->join('schools', 'schools.id', '=', 'school_teacher.school_id')
            ->join('candidates', 'candidates.teacher_id', '=', 'teachers.id')
            ->whereIn('school_teacher.school_id', $this->schoolIds)
            ->whereIn('school_teacher.teacher_id', $this->teacherIds)
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->where(function ($query) use ($search) {
                return $query
                    ->where('users.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('schools.name', 'LIKE', '%'.$search.'%');
            })
            ->select('school_teacher.school_id AS schoolId', 'school_teacher.teacher_id AS teacherId',
                'users.prefix_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix_name',
                'schools.name AS schoolName',
                DB::raw('COUNT(candidates.id) AS candidateCount'))
            ->groupBy(
                'school_teacher.school_id',
                'school_teacher.teacher_id',
                'users.prefix_name',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.suffix_name',
                'schools.name'
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->orderBy('schools.name');
    }

    private function getParticipatingTeacherAndSchoolIds(): array
    {
        return DB::table('candidates')
            ->where('version_id', $this->versionId)
            ->where('status', 'registered')
            ->distinct(['teacher_id', 'school_id'])
            ->select('teacher_id', 'school_id')
            ->get()
            ->toArray();
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new ParticipatingTeachersExport(
            $this->versionId,
            $this->schoolIds,
            $this->teacherIds
        ), 'participatingTeachers.csv');
    }
}
