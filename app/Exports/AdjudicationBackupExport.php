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
            $judgesArray = $room['judges']->toArray();
            $judges = array_filter($judgesArray, function ($judge) {
                return $judge['judge_type'] !== 'monitor';
            });

            $roomSheets = array_map(function ($judge) use ($room) {
                $judgeObj = \App\Models\Events\Versions\Scoring\Judge::find($judge['id']);
                return new RoomJudgeSheetExport($room, $judgeObj);
            }, $judges);

            $sheets = array_merge($sheets, $roomSheets);

        }

        return $sheets;
    }

}
