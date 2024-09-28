<?php

namespace App\Livewire\Events\Versions;

use App\Exports\TimeslotsExport;
use App\Livewire\BasePage;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigTimeslot;
use App\Models\Events\Versions\VersionTimeslot;
use App\Services\ConvertToNewYorkFromUtcService;
use App\Services\ConvertToUtcFromNewYorkService;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class TimeslotAssignmentComponent extends BasePage
{
    public array $assignedTimeslotSelectors = [];
    public array $columnHeaders = [];
    public int $duration = 0;
    public string $endTime = '';
    public int $lastTimeslotId = 0;
    public array $participatingSchoolIds = [];
    public bool $showSummaryTable = false;
    public string $startTime = '';
    public string $successDuration = '';
    public string $successEndTime = '';
    public string $successStartTime = '';
    public string $timeslot = '';
    public array $timeslots = [];
    public Version $version;
    public int $versionId = 0;
    public array $voicePartHeaders = [];

    public function mount(): void
    {
        parent::mount();

        $this->versionId = $this->dto['versionId'];
        $this->version = Version::find($this->versionId);
        $this->sortCol = $this->getSavedSortColumn('timeslot');
        $this->sortAsc = $this->getSavedSortAsc();

        $this->columnHeaders = $this->getColumnHeaders();

        $this->participatingSchoolIds = array_keys($this->getParticipatingSchools());

        $this->setTimeslotConfigurations();

        $this->timeslots = $this->getTimeslots();

        $this->lastTimeslotId = array_key_last($this->timeslots);

        $this->assignedTimeslotSelectors = $this->getAssignedTimeslotSelectors();
    }

    /** @todo */
    public function render()
    {
        $this->saveSortParameters();

        return view('livewire..events.versions.timeslot-assignment-component',
            [
                'assignedTimeslots' => $this->getAssignedTimes(),
                'rows' => $this->getRows(),
                'summary' => $this->getSummary(),
            ]);
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new TimeslotsExport, 'timeslots.csv');
    }

    public function sortBy(string $key): void
    {
        $this->sortColLabel = $key;

        $properties = [
            'school' => 'schools.name',
            'teacher' => 'teacher.last_name',
            'total' => 'vpCount',
            'timeslot' => 'timeslot',
        ];

        $requestedSort = $properties[$key];

        //toggle $this->sortAsc if user clicks on the same column header twice
        if ($requestedSort === $this->sortCol) {

            $this->sortAsc = (!$this->sortAsc);
        }

        $this->sortCol = $properties[$key];
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

    public function updatedAssignedTimeslotSelectors(): void
    {
        foreach ($this->assignedTimeslotSelectors as $schoolId => $timeslotIndex) {
            $timeslot = $this->timeslots[$timeslotIndex]['timestamp'];
            VersionTimeslot::query()
                ->where('version_id', $this->versionId)
                ->where('school_id', $schoolId)
                ->update(['timeslot' => $timeslot]);
        }
    }

    /** END OF PUBLIC FUNCTIONS **********************************************/

    private function generateKey(int $schoolId, int $teacherId): string
    {
        return $schoolId.'_'.$teacherId;
    }

    private function getAssignedTimes(): array
    {
        $timeslots = VersionTimeslot::query()
            ->where('version_id', $this->versionId)
            ->orderBy('school_id')
            ->get(['school_id', 'timeslot'])
            ->mapWithKeys(function ($item) {
                return [
                    $item->school_id => Carbon::createFromTimestamp($item->timeslot,
                        'America/New_York')->format('g:i a')
                ];
            })
            ->toArray();

        $this->addDefaultTimeslots($timeslots);

        return $timeslots;
    }

    /**
     * Return an arroy of [schoolId => timeslot index]
     * that captures the index of $this->timeslots based on the
     * timeslot value in VersionTimeslots
     * @return array
     */
    private function getAssignedTimeslotSelectors(): array
    {
        $selectors = [];

        foreach ($this->participatingSchoolIds as $schoolId) {

            $versionTimeslot = $this->getVersionTimeslot($schoolId);

            //isolate the full text value (YYYY-MM-DD HH:MM:SS) from UNIX timestamp
            $timestampToFind = ConvertToNewYorkFromUtcService::convert($versionTimeslot->timeslot);

            $index = $this->findTimeslotIndex($timestampToFind);

            //build the array
            if ($index !== false) {
                $selectors[$schoolId] = $index;
            }

        }

        return $selectors;
    }

    private function addDefaultTimeslots(array &$timeslots): void
    {
        foreach ($this->participatingSchoolIds as $schoolId) {

            if (!in_array($schoolId, $timeslots)) {

                $timeslots[$schoolId] = $this->timeslots[$this->lastTimeslotId];
            }
        }
    }

    private function findTimeslotIndex(string $timestampToFind): int
    {
        //isolate the full text value (YYYY-MM-DD HH:MM:SS) from the available event times
        static $timestamps = [];
        if (empty($timestamps)) {
            $timestamps = array_column($this->timeslots, 'timestamp');
        }

        //isolate the index from $timestamps matching the $timestampToFInd
        return array_search($timestampToFind, $timestamps);
    }

    private function getColumnHeaders(): array
    {
        $this->voicePartHeaders = $this->getColumnHeadersVoiceParts();

        $staticHeaders = $this->getColumnHeadersStatic();
//        $voicePartHeaders = $this->getColumnHeadersVoiceParts();
        $timeslot[] = ['label' => 'timeslot', 'sortBy' => 'timeslot'];

        return array_merge($staticHeaders, $this->voicePartHeaders, $timeslot);
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
                    $row->school_id,
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

    public function getSummary(): array
    {
        /**
         * array:8 [▼ // app\Livewire\Events\Versions\TimeslotAssignmentComponent.php:312
         * 0 => "2024-11-20 16:30:00"
         * 1 => "2024-11-20 16:46:00"
         * 2 => "2024-11-20 17:02:00"
         * 3 => "2024-11-20 17:18:00"
         * 4 => "2024-11-20 17:34:00"
         * 5 => "2024-11-20 17:50:00"
         * 6 => "2024-11-20 18:06:00"
         * 7 => "2024-11-20 18:22:00"
         * ]
         */
        $timeslots = VersionConfigTimeslot::where('version_id', $this->versionId)->first()->buildTimeslots();

        $summary = [];
        foreach ($timeslots as $key => $timeslot) {
            $summary[$key] = [
                'timeslot' => Carbon::parse($timeslot)->format('g:i a'),
                'schools' => $this->getTimeslotSchoolsCount($timeslot),
                'voiceparts' => $this->getTimeslotVoicePartCounts($timeslot),
            ];
        }

        return $summary;
    }

    private function getTimeslotSchoolsCount(string $timeslot): int
    {
        return VersionTimeslot::query()
            ->join('schools', 'schools.id', '=', 'version_timeslots.school_id')
            ->where('timeslot', $timeslot)
            ->count('schools.id');
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
            $timeslots[] = [
                'timestamp' => $start->format('Y-m-d G:i:s'),
                'selector' => $start->format('g:i a')
            ];

            //increment start
            $start->add($interval);
        }

        return $timeslots;
    }

    private function getTimeslotVoicePartCounts(string $timeslot): array
    {
        $voicePartCounts = [];

        $voicePartIds = $this->version->event->voiceParts->pluck('id')->toArray();

        foreach ($voicePartIds as $voicePartId) {
            $voicePartCounts[] = DB::table('candidates')
                ->join('version_timeslots', 'version_timeslots.school_id', '=', 'candidates.school_id')
                ->where('candidates.version_id', $this->versionId)
                ->where('candidates.status', 'registered')
                ->where('candidates.voice_part_id', $voicePartId)
                ->where('version_timeslots.timeslot', $timeslot)
                ->count('candidates.id');
        }

        //total
        $voicePartCounts[] = array_sum($voicePartCounts);

        return $voicePartCounts;
    }

    private function getVersionTimeslot(int $schoolId): VersionTimeslot
    {
        return VersionTimeslot::firstOrCreate(
            [
                'version_id' => $this->versionId,
                'school_id' => $schoolId,
            ],
            [
                'timeslot' => $this->timeslots[$this->lastTimeslotId]['timestamp'],
            ]
        );
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
//        $this->troubleShooting();
        $search = $this->search;

        return DB::table('candidates')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
            ->join('users AS teacher', 'teacher.id', '=', 'teachers.user_id')
            ->join('version_timeslots', 'version_timeslots.school_id', '=', 'schools.id')
            ->where('candidates.version_id', $this->versionId)
            ->where('version_timeslots.version_id', $this->versionId)
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
                'version_timeslots.timeslot'
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('schoolName')
            ->orderBy('teacher.last_name')
            ->orderBy('candidates.voice_part_id')
            ->groupBy('version_timeslots.timeslot')
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
        int $schoolId,
        string $teacherName,
        Collection $voiceParts
    ): array {
        $row = [
//            'counter' => $counter,
            'schoolId' => $schoolId,
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
