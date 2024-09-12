<?php

namespace App\Livewire\Events\Versions\Reports;

use App\Exports\StudentCountsExport;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StudentCountsComponent extends BasePageReports
{
    public array $columnHeaders = [];
    public array $summaryColumnHeaders = [];

    public function mount(): void
    {
        parent::mount();

        $this->sortCol = 'schoolName';
        $this->columnHeaders = $this->getColumnHeaders();
        $this->summaryColumnHeaders = $this->getSummaryColumnHeaders();

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

    public function render()
    {
        return view('livewire..events.versions.reports.student-counts-component',
            [
                'rows' => $this->getRows(),
                'summaryColumnHeaders' => $this->getSummaryColumnHeaders(),
                'summaryCounts' => $this->getSummaryCounts(),
            ]);
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new StudentCountsExport(
            $this->versionId,
        ), 'studentCounts.csv');
    }

    private function getColumnHeaders(): array
    {
        $staticHeaders = $this->getStaticColumnHeaders();
        $voicePartHeaders = $this->getVoicePartHeaders();

        return array_merge($staticHeaders, $voicePartHeaders);
    }

    private function getStaticColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'school', 'sortBy' => 'school'],
            ['label' => 'teacher', 'sortBy' => 'teacher']
        ];
    }

    private function getVoicePartHeaders(): array
    {
        $voiceParts = $this->version->event->VoiceParts->toArray();

        $voicePartHeaders = array_map(function ($voicePart) {
            return ['label' => $voicePart['abbr'], 'sortBy' => null];
        }, $voiceParts);

        $voicePartHeaders[] = ['label' => 'total', 'sortBy' => 'total'];

        return $voicePartHeaders;
    }

    private function getRows(): array
    {
        $search = $this->search;

        $detail = DB::table('candidates')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
            ->join('users AS teacher', 'teacher.id', '=', 'teachers.user_id')
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->where(function ($query) use ($search) {
                return $query->where('schools.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('teacher.last_name', 'LIKE', '%'.$search.'%');
            })
            ->distinct('candidates.school_id')
            ->select('schools.name AS schoolName', 'schools.id AS schoolId',
                'teacher.name AS teacherName', 'teacher.last_name', 'teacher.first_name', 'teacher.id AS teacherId',
                'candidates.voice_part_id',
                DB::raw('COUNT(voice_part_id) AS voicePartCount')
            )->groupBy('schoolName')
            ->groupBy('teacherName')
            ->groupBy('teacher.last_name')
            ->groupBy('teacher.first_name')
            ->groupBy('candidates.voice_part_id')
            ->groupBy('schoolId')
            ->groupBy('teacherId')
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('schoolName')
            ->orderBy('teacher.last_name')
            ->orderBy('teacher.first_name')
            ->get();

        return $this->flattenDetail($detail);
//        return DB::table('candidates')
//            ->join('schools', 'schools.id', '=', 'candidates.school_id')
//            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
//            ->join('users AS teacher', 'teacher.id', '=', 'teachers.user_id')
//            ->join('students', 'students.id', '=', 'candidates.student_id')
//            ->join('users AS student', 'student.id', '=', 'students.user_id')
//            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
//            ->where('candidates.version_id', $this->versionId)
//            ->where('candidates.status', 'registered')
//            ->where(function ($query) use ($search) {
//                return $query->where('schools.name', 'LIKE', '%'.$search.'%')
//                    ->orWhere('teacher.last_name', 'LIKE', '%'.$search.'%')
//                    ->orWhere('student.last_name', 'LIKE', '%'.$search.'%');
//            })
//            ->tap(function ($query) {
//                $this->filters->filterCandidatesByParticipatingSchools($query);
//                $this->filters->filterCandidatesByParticipatingClassOfs($query);
//                $this->filters->filterCandidatesByParticipatingVoiceParts($query);
//            })
//            ->select('candidates.id', 'candidates.voice_part_id',
//                'schools.name as schoolName',
//                DB::raw("CONCAT(teacher.last_name, ', ', teacher.first_name, ' ', teacher.middle_name) AS teacherFullName"),
//                'teacher.prefix_name', 'teacher.first_name', 'teacher.middle_name', 'teacher.last_name',
//                'teacher.suffix_name',
//                'student.first_name AS studentFirstName', 'student.middle_name AS studentMiddleName',
//                'student.last_name AS studentLastName', 'student.suffix_name AS studentSuffix',
//                'voice_parts.descr AS voicePartDescr', 'voice_parts.order_by',
//                'students.class_of',
//                DB::raw("((12 - (students.class_of - 2025))) AS grade")
//            )
//            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
//            ->orderBy('schools.name')
//            ->orderBy('studentLastName')
//            ->orderBy('studentFirstName')
//            ->orderBy('voice_parts.order_by');
    }

    private function flattenDetail(\Illuminate\Support\Collection $detail): array
    {
        $voicePartIds = $this->version->event->voiceParts->pluck('id')->toArray();

        dd($detail);
        $rows = [];
        foreach ($detail as $item) {

            $key = $item->schoolId.'_'.$item->teacherId;

            $rows[$key] = [
                'schoolName' => $item->schoolName,
                'teacherName' => $item->teacherName,
            ];

            foreach ($voicePartIds as $voicePartId) {

                //initialize value
                if (!array_key_exists($voicePartId, $rows[$key])) {
                    $rows[$key][$voicePartId] = 0;
                }

                if ((!$rows[$key][$voicePartId]) && ($item->voice_part_id == $voicePartId)) {
                    $rows[$key][$voicePartId] = $item->voicePartCount;
                }
            }
        }
        dd($rows);
        return $rows;
    }
}
