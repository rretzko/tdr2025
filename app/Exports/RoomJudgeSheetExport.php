<?php

namespace App\Exports;

use App\Models\Events\Versions\Judge;
use App\Models\Events\Versions\Room;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RoomJudgeSheetExport implements FromArray, WithTitle, WithHeadings
{
    public function __construct(private readonly Room $room, private readonly Judge $judge)
    {
    }

    public function array(): array
    {
        return $this->room->registrantsByIdArray;
    }

    public function headings(): array
    {
        $headings = [
            'Reg#',
            'Voice Part',
        ];

        $factors = $this->getRoomFactors();

        $tailings = ['total', 'comments'];

        return array_merge($headings, $factors, $tailings);
    }

    private function getRoomFactors(): array
    {
        $abbrs = [];

        $factors = DB::table('score_factors')
            ->join('room_score_categories', 'room_score_categories.score_category_id', '=',
                'score_factors.score_category_id')
            ->join('score_categories', 'score_categories.id', '=', 'score_factors.score_category_id')
            ->where('room_score_categories.room_id', $this->room->id)
            ->select('score_factors.factor', 'score_factors.abbr', 'score_factors.order_by',
                'score_categories.descr')
            ->orderBy('score_factors.order_by')
            ->get();

        foreach ($factors as $factor) {
            $abbrs[] = $factor->abbr;
        }

        return $abbrs;
    }

    public function title(): string
    {
        $roomName = Str::camel($this->room->room_name);
        $user = $this->judge['user'];
        $judgeName = $user->last_name.strToUpper(substr($user->first_name, 0, 1));

        return $roomName.'-'.$judgeName;
    }
}
