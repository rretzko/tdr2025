<?php

namespace App\Livewire\Events\Versions\Reports;


use App\Exports\ParticipatingStudentsExport;
use App\Livewire\Forms\RoomForm;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ParticipatingStudentsComponent extends BasePageReports
{
    public array $columnHeaders = [];
    public array $summaryColumnHeaders = [];

    public function mount(): void
    {
        parent::mount();

        $this->hasFilters = true;
        $this->columnHeaders = $this->getColumnHeaders();
        $this->summaryColumnHeaders = $this->getSummaryColumnHeaders();

        //sorts
        $this->sortCol = $this->userSort ? $this->userSort->column : 'schoolName';
        $this->sortAsc = $this->userSort ? $this->userSort->asc : $this->sortAsc;
        $this->sortColLabel = $this->userSort ? $this->userSort->label : 'school';

        //filters
        $this->filters->participatingClassOfsSelectedIds = $this->filters->previousFilterExists('participatingClassOfsSelectedIds',
            $this->dto['header'])
            ? $this->filters->getPreviousFilterArray('participatingClassOfsSelectedIds', $this->dto['header'])
            : $this->filters->participatingClassOfsSelectedIds;

        $this->filters->participatingSchoolsSelectedIds = $this->filters->previousFilterExists('participatingSchoolsSelectedIds',
            $this->dto['header'])
            ? $this->filters->getPreviousFilterArray('participatingSchoolsSelectedIds', $this->dto['header'])
            : $this->filters->participatingSchoolsSelectedIds;

        $this->filters->participatingVoicePartsSelectedIds = $this->filters->previousFilterExists('participatingVoicePartsSelectedIds',
            $this->dto['header'])
            ? $this->filters->getPreviousFilterArray('participatingVoicePartsSelectedIds', $this->dto['header'])
            : $this->filters->participatingVoicePartsSelectedIds;

        //filterMethods
        if (count($this->filters->participatingSchoolsSelectedIds) > 1) {
            $this->filterMethods[] = 'participatingSchools';
        }
        if (count($this->filters->participatingClassOfsSelectedIds) > 1) {
            $this->filterMethods[] = 'participatingClassOfs';
        }
        if (count($this->filters->participatingVoicePartsSelectedIds) > 1) {
            $this->filterMethods[] = 'participatingVoiceParts';
        }
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'school', 'sortBy' => 'school'],
            ['label' => 'teacher', 'sortBy' => 'teacher'], //users.last_name
            ['label' => 'registrant', 'sortBy' => 'registrant'],
            ['label' => 'grade', 'sortBy' => 'classOf'],
            ['label' => 'voice part', 'sortBy' => 'voicePart'],
        ];
    }

    private function getCountOfRegistrants(int $voicePartId): int
    {
        return Candidate::query()
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->where('candidates.voice_part_id', $voicePartId)
            ->whereIn('candidates.school_id', $this->filters->participatingSchoolsSelectedIds)
            ->whereIn('students.class_of', $this->filters->participatingClassOfsSelectedIds)
            ->whereIn('candidates.voice_part_id', $this->filters->participatingVoicePartsSelectedIds)
            ->count('candidates.id');
    }

    public function render()
    {
        $this->saveSortParameters();

        $this->filters->setFilter('participatingSchoolsSelectedIds', $this->dto['header']);
        $this->filters->setFilter('participatingClassOfsSelectedIds', $this->dto['header']);
        $this->filters->setFilter('participatingVoicePartsSelectedIds', $this->dto['header']);

        return view('livewire..events.versions.reports.participating-students-component',
            [
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
                'summaryCounts' => $this->getSummaryCounts(),
            ]);
    }

    private function getRows(): Builder
    {
        //$this->test();

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
            ->tap(function ($query) {
                $this->filters->filterCandidatesByParticipatingSchools($query);
                $this->filters->filterCandidatesByParticipatingClassOfs($query);
                $this->filters->filterCandidatesByParticipatingVoiceParts($query);
            })
            ->select('candidates.id', 'candidates.voice_part_id',
                'schools.name as schoolName',
                DB::raw("CONCAT(teacher.last_name, ', ', teacher.first_name, ' ', teacher.middle_name) AS teacherFullName"),
                'teacher.prefix_name', 'teacher.first_name', 'teacher.middle_name', 'teacher.last_name',
                'teacher.suffix_name',
                'student.first_name AS studentFirstName', 'student.middle_name AS studentMiddleName',
                'student.last_name AS studentLastName', 'student.suffix_name AS studentSuffix',
                'voice_parts.descr AS voicePartDescr', 'voice_parts.order_by',
                'students.class_of',
                DB::raw("((12 - (students.class_of - 2025))) AS grade")
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
