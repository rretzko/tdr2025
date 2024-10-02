<?php

namespace App\Exports;

use App\Models\Events\Versions\Judge;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Version;
use App\Models\UserConfig;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AdjudicationBackupExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(private readonly Collection $rooms)
    {
    }

    public function sheets(): array
    {
        if (empty($this->rooms)) {
            return [];
        }

        $sheets = [];

        foreach ($this->rooms as $room) {

            //exclude the monitor role
            $judges = array_filter($room['judges'], function ($judge) {
                return $judge->judge_type !== 'monitor';
            });

            $roomSheets = array_map(function ($judge) use ($room) {
                return new RoomJudgeSheetExport($room, $judge);
            }, $judges);

            $sheets = array_merge($sheets, $roomSheets);
//            foreach ($judges as $judge) {
//                $sheets[] = new RoomJudgeSheetExport($room, $judge);
//            }
        }

        return $sheets;
    }

}
