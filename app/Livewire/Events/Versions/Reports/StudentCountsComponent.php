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
    }

    public function render()
    {
        $this->saveSortParameters();

        return view('livewire..events.versions.reports.student-counts-component',
            [
                'rows' => $this->getRows(),
                'summaryColumnHeaders' => $this->getSummaryColumnHeaders(),
                'summaryCounts' => $this->getSummaryCounts(),
            ]);
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        //clear any artifacts
        $this->reset('search');

        return Excel::download(new StudentCountsExport(
            $this->getRows(),
            $this->version->event->voiceParts
        ), 'studentCounts.csv');
    }

    private function getColumnHeaders(): array
    {
        $staticHeaders = $this->getColumnHeadersStatic();
        $voicePartHeaders = $this->getColumnHeadersVoiceParts();

        return array_merge($staticHeaders, $voicePartHeaders);
    }

    private function getColumnHeadersStatic(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'school', 'sortBy' => 'school'],
            ['label' => 'teacher', 'sortBy' => 'teacher']
        ];
    }

    private function getColumnHeadersVoiceParts(): array
    {
        $voiceParts = $this->version->event->VoiceParts->toArray();

        $voicePartHeaders = array_map(function ($voicePart) {
            return ['label' => $voicePart['abbr'], 'sortBy' => null];
        }, $voiceParts);

        $voicePartHeaders[] = ['label' => 'total', 'sortBy' => 'total'];

        return $voicePartHeaders;
    }

    private function generateKey(int $schoolId, int $teacherId): string
    {
        return $schoolId.'_'.$teacherId;
    }

    /**
     * @return Collection of individual row for
     * - school_id
     * - schoolName
     * - teacherName
     * - last_name (of teacher for sorting)
     * - voice_part_id
     * - vpCount (count of voice_part_id instances within registered candidates in school_id at version_id)
     */
    private function getVoicePartCountsQuery(): Collection
    {
        $search = $this->search;
//$this->test($search);
        return DB::table('candidates')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
            ->join('users AS teacher', 'teacher.id', '=', 'teachers.user_id')
            ->leftJoin('phone_numbers as mobile', function ($join) {
                $join->on('mobile.user_id', '=', 'teachers.user_id')
                    ->where('mobile.phone_type', '=',
                        'mobile'); // Assuming there's a type column to distinguish phone types
            })
            ->leftJoin('phone_numbers as work', function ($join) {
                $join->on('work.user_id', '=', 'teachers.user_id')
                    ->where('work.phone_type', '=',
                        'work'); // Assuming there's a type column to distinguish phone types
            })
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->where(function ($query) use ($search) {
                return $query->where('schools.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('teacher.last_name', 'LIKE', '%'.$search.'%');
            })
            ->tap(function ($query) {
//                $this->filters->filterCandidatesByParticipatingSchools($query);
//                $this->filters->filterCandidatesByParticipatingClassOfs($query);
//                $this->filters->filterCandidatesByParticipatingVoiceParts($query);
            })
            ->select('candidates.school_id',
                'schools.name AS schoolName',
                'candidates.teacher_id',
                'teacher.name AS teacherName',
                'teacher.last_name',
                'candidates.voice_part_id',
                DB::raw('COUNT(candidates.voice_part_id) AS vpCount'),
                'teacher.email',
                'mobile.phone_number AS phoneMobile',
                'work.phone_number AS phoneWork',
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('schoolName')
            ->orderBy('teacher.last_name')
            ->orderBy('candidates.voice_part_id')
            ->groupBy('schools.name')
            ->groupBy('candidates.school_id')
            ->groupBy('teacher.name')
            ->groupBy('teacher.last_name')
            ->groupBy('candidates.teacher_id')
            ->groupBy('candidates.voice_part_id')
            ->groupBy('teacher.email')
            ->groupBy('mobile.phone_number')
            ->groupBy('work.phone_number')
            ->get();
    }

    private function getRows(): array
    {
        $rows = [];
        $collection = $this->getVoicePartCountsQuery();
        $voiceParts = $this->version->event->voiceParts;
        $counter = 1;

        //iterate through schools to build array
        foreach ($collection as $row) {

            //generate array $key
            $key = $this->generateKey($row->school_id, $row->teacher_id);

            //initialize array
            if (!isset($rows[$key])) {
                $rows[$key] = $this->initializeRow(
                    $counter++,
                    $row->schoolName,
                    $this->makeTeacherBlock($row),
                    $voiceParts
                );
            }

            //update voice part counts with correct values
            $this->updateVoicePartCounts($rows[$key], $row, $voiceParts);
        } //end foreach

        //re-sort final array by totals if requested
        if ($this->sortColLabel === 'total') {
            $rows = $this->reSortRowsByTotal($rows);
        }

        return $rows;
    }

    /**
     * Note: The order of the array properties matches the column layout in studentCountsTable
     * DO NOT re-order these properties!
     * @param  int  $counter
     * @param  string  $schoolName
     * @param  string  $teacherName
     * @param  Collection  $voiceParts
     * @return array
     */
    private function initializeRow(
        int $counter,
        string $schoolName,
        string $teacherName,
        Collection $voiceParts
    ): array {
        $row = [
            'counter' => $counter,
            'schoolName' => $schoolName,
            'teacherName' => $teacherName,
        ];

        //if missing, initialize all possible voice parts values @ 0
        foreach ($voiceParts as $voicePart) {
            $row[$voicePart->id] = 0;
        }

        $row['total'] = 0;

        return $row;
    }

    /**
     * @param  Collection  $row
     * @return string0 => {#2775 ▼
     * +"school_id": 6846
     * +"schoolName": "Bridgewater-Raritan High School"
     * +"teacher_id": 17376
     * +"teacherName": "Lisa Rotondi"
     * +"last_name": "Rotondi"
     * +"voice_part_id": 63
     * +"vpCount": 3
     * +"email": "lrotondi@brrsd.k12.nj.us"
     * +"phoneMobile": null
     * +"phoneWork": null
     * }
     */
    private function makeTeacherBlock(\stdClass $row): string
    {
        $str = '<div>'.$row->teacherName.'</div>';

        $str .= '<div class="ml-2 text-sm">'.$row->email.'</div>';

        $str .= '<div class="ml-2 text-sm">'.$row->phoneMobile.' (c)</div>';

        $str .= '<div class="ml-2 text-sm">'.$row->phoneWork.' (w)</div>';

        return $str;
    }

    private function reSortRowsByTotal(array $rows): array
    {
        // Extract the values and sort them
        $values = array_values($rows);
        usort($values, function ($a, $b) {
            return ($this->sortAsc)
                ? $a['total'] <=> $b['total']
                : $b['total'] <=> $a['total'];
        });

        // Rebuild the array with the original keys
        $sortedArray = [];
        foreach ($values as $value) {
            foreach ($rows as $key => $originalValue) {
                if ($value === $originalValue) {
                    $sortedArray[$key] = $value;
                    break;
                }
            }
        }

        return $sortedArray;
    }

    private function updateVoicePartCounts(
        array &$row,
        \stdClass $dataRow,
        Collection $voiceParts
    ) {
        foreach ($voiceParts as $voicePart) {
            if ($dataRow->voice_part_id == $voicePart->id) {
                $row[$voicePart->id] = $dataRow->vpCount;
                $row['total'] += $dataRow->vpCount;
            }
        }
    }
}
