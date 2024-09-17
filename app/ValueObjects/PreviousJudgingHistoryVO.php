<?php

namespace App\ValueObjects;

use App\Models\Events\Versions\Judge;
use App\Models\Events\Versions\Version;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class PreviousJudgingHistoryVO
{
    private string $history = '... getting previous judging history...';

    public function __construct(private readonly Version $version, private int $userId)
    {
        $this->init();
    }

    private function init()
    {
        $versionIds = $this->version->event->versions->pluck('id')->toArray();

        $assignments = $this->getAssignments($versionIds);

        $this->history = $this->buildTable($assignments);
    }

    private function getAssignments(array $versionIds): Collection
    {
        return Judge::query()
            ->join('versions', 'versions.id', '=', 'judges.version_id')
            ->join('rooms', 'rooms.id', '=', 'judges.room_id')
            ->where('judges.user_id', $this->userId)
            ->whereIn('judges.version_id', $versionIds)
            ->select('versions.senior_class_of', 'rooms.room_name')
            ->orderByDesc('versions.senior_class_of')
            ->get();
    }

    private function buildTable(Collection $assignments): string
    {
        $table = '<style>'
            .'td,th{border: 1px solid black; text-align: center;padding: 0 0.25rem;}'
            .'</style>';
        $table .= '<table class="history"><thead><tr><th>Year</th><th>Room</th></tr></thead><tbody>';

        if ($assignments->isEmpty()) {
            $table .= '<tr><td colspan="2">None Found</td></tr>';

        } else {
            foreach ($assignments as $assignment) {
                $table .= '<tr>'
                    .'<td>'
                    .$assignment->senior_class_of
                    .'</td>'
                    .'<td>'
                    .$assignment->room_name
                    .'</td>'
                    .'</tr>';
            }
        }

        $table .= '</tbody></table>';

        return $table;
    }

    public function getHistory()
    {
        return $this->history;
    }
}
