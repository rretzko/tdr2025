<?php

namespace App\Services;

use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\VersionConfigAdjudication;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TabroomTrackingBulletsService
{
    public int $studentCount = 0;
    private array $candidates = [];

    public function __construct(private int $versionId, private int $roomId = 0, private readonly array $statuses = [])
    {
        $this->init();
    }

    public function getCandidates(): array
    {
        return $this->candidates;
    }

    private function init(): void
    {
        if (!$this->roomId) {
            return;
        }

        $room = Room::find($this->roomId);
        if (!$room) {
            return;
        }

        $judges = $room->judges;
        $judgeIds = $judges->pluck('id')->all();
        $factorCount = $room->scoringFactors->count();
        $maxScoreCount = $factorCount * count($judgeIds);
        $roomTolerance = (int) $room->tolerance;
        $judgeCountForTolerance = (int) VersionConfigAdjudication::where('version_id', $room->version_id)
            ->value('judge_per_room_count');

        $candidateRows = DB::table('candidates')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->join('room_voice_parts', 'room_voice_parts.voice_part_id', '=', 'candidates.voice_part_id')
            ->leftJoin('schools', 'schools.id', '=', 'candidates.school_id')
            ->leftJoin('teachers', 'teachers.id', '=', 'candidates.teacher_id')
            ->leftJoin('users AS teacher_users', 'teacher_users.id', '=', 'teachers.user_id')
            ->leftJoin('version_timeslots', function ($join) {
                $join->on('version_timeslots.school_id', '=', 'candidates.school_id')
                    ->on('version_timeslots.version_id', '=', 'candidates.version_id');
            })
            ->where('room_voice_parts.room_id', $room->id)
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->distinct()
            ->select(
                'candidates.id',
                'candidates.program_name',
                'candidates.school_id',
                'voice_parts.id AS voice_part_id',
                'voice_parts.descr AS voice_part_descr',
                'voice_parts.order_by AS voice_part_order_by',
                'schools.name AS school_name',
                'teacher_users.name AS teacher_name',
                'version_timeslots.timeslot AS school_timeslot',
            )
            ->orderBy('voice_parts.order_by')
            ->orderBy('candidates.id')
            ->get();

        $candidateIds = $candidateRows->pluck('id')->all();
        $this->studentCount = count($candidateIds);

        if (empty($candidateIds)) {
            return;
        }

        $aggByCandidate = [];
        if (!empty($judgeIds)) {
            $scoreAggs = DB::table('scores')
                ->whereIn('candidate_id', $candidateIds)
                ->whereIn('judge_id', $judgeIds)
                ->selectRaw('candidate_id, judge_id, COUNT(*) AS cnt, SUM(score) AS total')
                ->groupBy('candidate_id', 'judge_id')
                ->get();

            foreach ($scoreAggs as $row) {
                $aggByCandidate[$row->candidate_id][$row->judge_id] = [
                    'cnt' => (int) $row->cnt,
                    'total' => (int) $row->total,
                ];
            }
        }

        $byVoicePart = [];

        foreach ($candidateRows as $row) {
            $agg = $aggByCandidate[$row->id] ?? [];

            $totalScoreCount = 0;
            foreach ($agg as $judgeAgg) {
                $totalScoreCount += $judgeAgg['cnt'];
            }

            if ($totalScoreCount === 0) {
                $status = 'pending';
            } elseif ($totalScoreCount === $maxScoreCount) {
                $status = 'completed';
            } elseif ($totalScoreCount < $maxScoreCount) {
                $status = 'wip';
            } else {
                $status = 'errors';
            }

            $judgesScored = count($agg);
            if ($judgesScored !== $judgeCountForTolerance) {
                $inTolerance = true;
            } else {
                $totals = array_column($agg, 'total');
                $inTolerance = (max($totals) - min($totals)) <= $roomTolerance;
            }

            $schoolShort = $this->shortenSchoolName($row->school_name ?? '');
            $teacherName = $row->teacher_name ?? '';
            $title = $row->program_name."\n"
                .$teacherName.' @ '.$schoolShort."\n";

            foreach ($judges as $judge) {
                $jAgg = $agg[$judge->id] ?? ['cnt' => 0, 'total' => 0];
                $title .= $judge->user->name
                    .': '.$jAgg['cnt']
                    .'/'.$factorCount
                    .' ('.$jAgg['total'].')'
                    ."\n";
            }

            $timeslotFormatted = '';
            if ($row->school_timeslot) {
                $timeslotFormatted = Carbon::parse($row->school_timeslot)->subHour(5)->format('h:i A');
                $title .= $timeslotFormatted."\n";
            }

            $byVoicePart[$row->voice_part_id]['descr'] = $row->voice_part_descr;
            $byVoicePart[$row->voice_part_id]['orderBy'] = $row->voice_part_order_by;
            $byVoicePart[$row->voice_part_id]['candidates'][] = [
                'candidateId' => $row->id,
                'voicePartDescr' => $row->voice_part_descr,
                'statusColors' => $this->statuses[$status] ?? '',
                'title' => $title,
                'tolerance' => $inTolerance,
                'timeslot' => $timeslotFormatted,
            ];
        }

        uasort($byVoicePart, fn($a, $b) => $a['orderBy'] <=> $b['orderBy']);

        foreach ($byVoicePart as $entry) {
            $this->candidates[] = [
                'roomName' => $room->room_name,
                'tolerance' => $room->tolerance,
                'candidates' => [
                    'voicePartDescr' => $entry['descr'],
                    'candidates' => $entry['candidates'],
                ],
            ];
        }
    }

    private function shortenSchoolName(string $name): string
    {
        $replacements = [
            'Regional High School' => 'RHS',
            'Regional Middle School' => 'RMS',
            'Senior High School' => 'Sr HS',
            'High School' => 'HS',
            'Middle School' => 'MS',
            'Junior/Senior' => 'J/S',
            'Junior/senior' => 'J/S',
            'Elementary School' => 'ES',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $name);
    }
}
