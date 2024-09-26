<?php

namespace App\Livewire\Events\Versions;

use App\Exports\TimeslotsExport;
use App\Livewire\BasePage;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigTimeslot;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TimeslotAssignmentComponent extends BasePage
{
    public array $columnHeaders = [];
    public int $duration = 0;
    public string $endTime = '';
    public string $startTime = '';
    public string $successDuration = '';
    public string $successEndTime = '';
    public string $successStartTime = '';
    public string $timeslot = '';
    public array $timeslots = [];
    public Version $version;
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->versionId = $this->dto['versionId'];
        $this->version = Version::find($this->versionId);
        $this->sortCol = 'schoolName';

        $this->columnHeaders = $this->getColumnHeaders();

        $this->setTimeslotConfigurations();

        $this->timeslots = $this->getTimeslots();
    }

    /** @todo */
    public function render()
    {
        return view('livewire..events.versions.timeslot-assignment-component',
            [
                'rows' => $this->getRows(),
            ]);
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new TimeslotsExport, 'timeslots.csv');
    }

    public function updatedDuration(): void
    {
        $this->reset('successDuration');

        VersionConfigTimeslot::updateOrCreate(
            [
                'version_id' => $this->versionId,
            ],
            [
                'duration' => $this->duration,
            ]
        );

        $this->successDuration = 'Updated.';

        $this->timeslots = $this->getTimeslots();
    }

    public function updatedEndTime(): void
    {
        $this->reset('successEndTime');

        //convert time to UTC timezon
        $utcTime = Carbon::parse($this->endTime, 'UTC');

        VersionConfigTimeslot::updateOrCreate(
            [
                'version_id' => $this->versionId,
            ],
            [
                'end_time' => Carbon::parse($utcTime),
            ]
        );

        $this->successEndTime = 'Updated.';
    }

    public function updatedStartTime(): void
    {
        $this->reset('successStartTime');

        VersionConfigTimeslot::updateOrCreate(
            [
                'version_id' => $this->versionId,
            ],
            [
                'start_time' => $this->startTime,
            ]
        );

        $this->successStartTime = 'Updated.';
    }

    public function updatedTimeslot(): void
    {
        $parts = explode('_', $this->timeslot);
        $schoolId = $parts[0];
        $teacherId = $parts[1];
        $timeslot = $this->timeslots[$parts[2]];

        dd($schoolId.'.'.$teacherId.'.'.$timeslot);
    }

    /** END OF PUBLIC FUNCTIONS **********************************************/

    private function generateKey(int $schoolId, int $teacherId): string
    {
        return $schoolId.'_'.$teacherId;
    }

    private function getColumnHeaders(): array
    {
        $staticHeaders = $this->getColumnHeadersStatic();
        $voicePartHeaders = $this->getColumnHeadersVoiceParts();
        $voicePartHeaders[] = ['label' => 'timeslot', 'sortBy' => 'timeslot'];

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
                    $row->teacherName,
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

    private function getTimeslots(): array
    {
        $defaultTime = Carbon::now('America/New_York')->addHours(5);
        $vct = VersionConfigTimeslot::where('version_id', $this->versionId)->first();
        $startTime = Carbon::parse($vct->start_time ?? $defaultTime)->subHours(5);
        $endTime = Carbon::parse($vct->end_time ?? $defaultTime)->subHours(5);
        $duration = $vct->duration ?? 15; // minutes

        $start = new DateTime($startTime);
        $end = new DateTime($endTime);
        $interval = new DateInterval('PT'.$duration.'M');
        $latestTimeslot = $end->add($interval);
        $timeslots = [];

        while ($start < $latestTimeslot) {
            $timeslots[] = $start->format('h:i a');
            $start->add($interval);
        }

        return $timeslots;
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
            ->get();
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
//            'counter' => $counter,
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

    private function setTimeslotConfigurations(): void
    {
        $configs = VersionConfigTimeslot::firstOrCreate(
            [
                'version_id' => $this->versionId
            ]
        );

        $this->duration = $configs->duration ?? 15;
        $defaultTime = Carbon::now()->addHours(5);
        $this->endTime = Carbon::parse($configs->end_time ?? $defaultTime)->subHours(5)->format('Y-m-d H:i:s') ?? '';
        $this->startTime = Carbon::parse($configs->start_time ?? $defaultTime)->subHours(5)->format('Y-m-d H:i:s') ?? '';
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
