<?php

namespace App\Exports;

use App\Models\UserConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JudgeAssignmentsExport implements FromArray, WithHeadings
{
    private array $rows = [];
    private int $versionId = 0;

    public function __construct()
    {
        $this->versionId = UserConfig::getValue('versionId');
        $this->rows = $this->getRows();
    }

    private function getRows()
    {
        $rooms = DB::table('rooms')
            ->where('rooms.version_id', $this->versionId)
            ->select('id', 'room_name', 'tolerance', 'order_by')
            ->orderBy('rooms.order_by')
            ->get()
            ->toArray();

        $voiceParts = $this->getVoiceParts($rooms);

        $scoreCategories = $this->getScoreCategories($rooms);

        $judges = $this->getJudges($rooms);

        return $this->buildRows($rooms, $voiceParts, $scoreCategories, $judges);
    }

    private function getVoiceParts(array $rooms): array
    {
        $voiceParts = [];
        foreach ($rooms as $room) {
            $voiceParts[$room->id] = DB::table('room_voice_parts')
                ->join('voice_parts', 'voice_parts.id', '=', 'room_voice_parts.voice_part_id')
                ->where('room_id', $room->id)
                ->select('voice_parts.descr')
                ->pluck('voice_parts.descr')
                ->toArray();
        }

        return $voiceParts;
    }

    private function getScoreCategories(array $rooms): array
    {
        $scoreCategories = [];
        foreach ($rooms as $room) {
            $scoreCategories[$room->id] = DB::table('room_score_categories')
                ->join('score_categories', 'score_categories.id', '=', 'room_score_categories.score_category_id')
                ->where('room_id', $room->id)
                ->select('score_categories.descr')
                ->pluck('score_categories.descr')
                ->toArray();
        }

        return $scoreCategories;
    }

    private function getJudges(array $rooms): array
    {
        $judges = [];

        foreach ($rooms as $room) {
            $judges[$room->id] = [
                'headJudge' => $this->getJudgeName($room->id, 'head judge'),
                'judge2' => $this->getJudgeName($room->id, 'judge 2'),
                'judge3' => $this->getJudgeName($room->id, 'judge 3'),
                'judge4' => $this->getJudgeName($room->id, 'judge 4'),
                'judgeMonitor' => $this->getJudgeName($room->id, 'judge monitor'),
                'monitor' => $this->getJudgeName($room->id, 'monitor'),
            ];
        }

        return $judges;
    }

    private function getJudgeName(int $roomId, string $judgeType): string
    {
        return DB::table('judges')
            ->join('users', 'users.id', '=', 'judges.user_id')
            ->where('judges.room_id', $roomId)
            ->where('judges.judge_type', $judgeType)
            ->value('users.name') ?? 'none';
    }

    private function buildRows(
        array $rooms,
        array $voiceParts,
        array $scoreCategories,
        array $judges
    ): array {
        $rows = [];

        foreach ($rooms as $room) {

            $roomId = $room->id;

            $rows[] = [
                $room->room_name,
                implode(',', $voiceParts[$roomId]),
                implode(',', $scoreCategories[$roomId]),
                $judges[$roomId]['headJudge'],
                $judges[$roomId]['judge2'],
                $judges[$roomId]['judge3'],
                $judges[$roomId]['judge4'],
                $judges[$roomId]['judgeMonitor'],
                $judges[$roomId]['monitor'],
                $room->tolerance,
            ];
        }

        return $rows;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'room',
            'voice parts',
            'score categories',
            'head judge',
            'judge 2',
            'judge 3',
            'judge 4',
            'judge monitor',
            'monitor',
            'tolerance',
        ];
    }
}
