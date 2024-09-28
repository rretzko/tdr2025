<?php

namespace App\Exports;

use App\Models\Events\Versions\Version;
use App\Models\UserConfig;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimeslotsExport implements FromArray, WithHeadings
{
    private array $rows = [];
    private Version $version;
    private int $versionId = 0;

    public function __construct()
    {
        $this->versionId = UserConfig::getValue('versionId');
        $this->version = Version::find($this->versionId);
    }

    public function array(): array
    {
        return $this->getRows();
    }

    public function headings(): array
    {
        $headers = [
            'timeslot', 'school', 'teacher'
        ];

        $merged = array_merge($headers, $this->voicePartHeaders());

        $merged[] = 'total';

        return $merged;
    }

    private function generateKey(int $schoolId, int $teacherId): string
    {
        return $schoolId.'_'.$teacherId;
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
            if (!isset($this->rows[$key])) {
                $rows[$key] = $this->initializeRow(
                    $counter++,
                    $row->schoolName,
                    $row->school_id,
                    $row->teacherName,
                    $voiceParts,
                    $row->timeslot,
                );
            }

            //update voice part counts with correct values
            $this->updateVoicePartCounts($rows[$key], $row, $voiceParts);
        } //end foreach

        return $rows;
    }

    private function getVoicePartCountsQuery(): Collection
    {
        return DB::table('candidates')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
            ->join('users AS teacher', 'teacher.id', '=', 'teachers.user_id')
            ->join('version_timeslots', 'version_timeslots.school_id', '=', 'schools.id')
            ->where('candidates.version_id', $this->versionId)
            ->where('version_timeslots.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->select('version_timeslots.timeslot', 'candidates.school_id',
                'schools.name AS schoolName',
                'candidates.teacher_id',
                'teacher.name AS teacherName',
                'teacher.last_name',
                'candidates.voice_part_id',
                DB::raw('COUNT(candidates.voice_part_id) AS vpCount'),
            )
            ->orderBy('timeslot', 'asc')
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

    private function initializeRow(
        int $counter,
        string $schoolName,
        int $schoolId,
        string $teacherName,
        Collection $voiceParts,
        string $timeslot,
    ): array {
        $row = [
            'timeslot' => Carbon::parse($timeslot)->format('g:i a'),
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

//    private function stripArrayColumns(array $rows): array
//    {
//        $cleaned = [];
//        foreach($rows AS $row){
//
//            //remove first property (schoolId)
//            array_shift($row);
//
//            $cleaned[] = $row;
//        }
//
//        return $cleaned;
//    }

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

    private function voicePartHeaders(): array
    {
        $voiceParts = $this->version->event->voiceParts->toArray();

        return array_map(function ($voicePart) {
            return $voicePart['abbr'];
        }, $voiceParts);
    }
}
