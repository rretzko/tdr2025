<?php

namespace App\Livewire\Events\Versions\Reports;


use App\Exports\ParticipatingStudentsExport;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ParticipatingStudentsComponent extends BasePageReports
{
    public array $columnHeaders = [];

    public function mount(): void
    {
        parent::mount();

        $this->columnHeaders = $this->getColumnHeaders();
        $this->sortCol = 'schoolName';

    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'school', 'sortBy' => 'school'],
            ['label' => 'teacher', 'sortBy' => 'teacher'], //users.last_name
            ['label' => 'registrant', 'sortBy' => 'registrant'],
            ['label' => 'voice part', 'sortBy' => 'voicePart'],
        ];
    }

    public function render()
    {
        return view('livewire..events.versions.reports.participating-students-component',
            [
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
            ]);
    }

    private function getRows(): Builder
    {
        $search = $this->search;

        return DB::table('candidates')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
            ->join('users AS teacher', 'teacher.id', '=', 'teachers.user_id')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users AS student', 'student.id', '=', 'students.user_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->where(function ($query) use ($search) {
                return $query->where('schools.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('teacher.last_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('student.last_name', 'LIKE', '%'.$search.'%');
            })
            ->select('candidates.id', 'candidates.voice_part_id',
                'schools.name as schoolName',
                DB::raw("CONCAT(teacher.last_name, ' ', teacher.suffix_name, ', ', teacher.first_name, ' ', teacher.middle_name) AS teacherFullName"),
                'teacher.prefix_name', 'teacher.first_name', 'teacher.middle_name', 'teacher.last_name',
                'teacher.suffix_name',
                'student.first_name AS studentFirstName', 'student.middle_name AS studentMiddleName',
                'student.last_name AS studentLastName', 'student.suffix_name AS studentSuffix',
                'voice_parts.descr AS voicePartDescr', 'voice_parts.order_by'
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('schools.name')
            ->orderBy('studentLastName')
            ->orderBy('studentFirstName')
            ->orderBy('voice_parts.order_by');
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new ParticipatingStudentsExport(
            $this->versionId,
        ), 'participatingStudents.csv');
    }
}
