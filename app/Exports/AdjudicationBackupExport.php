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
        $sheets = [];

        foreach ($this->rooms as $room) {

            foreach ($room['judges'] as $judge) {

                if ($judge->judge_type !== 'monitor') {
                    $sheets[] = new RoomJudgeSheetExport($room, $judge);
                }
            }
        }

        return $sheets;
    }

}
