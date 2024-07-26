<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class VersionsTableService
{
    private array $rows = [];

    public function __construct(private int $eventId)
    {
        $this->init();
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function init(): void
    {
        $this->rows = DB::table('versions')
            ->where('event_id', $this->eventId)
            ->select('versions.id', 'versions.name', 'versions.senior_class_of AS classOf', 'versions.status')
            ->orderByDesc('versions.senior_class_of')
            ->get()
            ->toArray();
    }

    public function getTableRows(): array
    {
        return $this->rows;
    }


}
