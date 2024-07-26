<?php

namespace App\Livewire\Events;

use App\Livewire\BasePage;
use Illuminate\Support\Facades\DB;

class EventParticipationTableComponent extends BasePage
{
    public function render()
    {
        return view('livewire..events.event-participation-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows(),
            ]);
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'event', 'sortBy' => null],
            ['label' => 'version', 'sortBy' => null],
            ['label' => 'status', 'sortBy' => null],
        ];
    }

    private function getRows(): array
    {
        $invited = $this->getRowsInvited();
        $past = $this->getRowsPast();

        return array_merge($invited, $past);
    }

    private function getRowsInvited(): array
    {
        return DB::table('version_participants')
            ->join('versions', 'versions.id', '=', 'version_participants.version_id')
            ->join('events', 'events.id', '=', 'versions.event_id')
            ->where('version_participants.user_id', auth()->id())
            ->where('versions.status', 'active')
            ->select('version_participants.version_id AS id',
                'events.short_name AS eventName',
                'versions.short_name AS versionName', 'versions.status')
            ->get()
            ->toArray();
    }

    private function getRowsPast(): array
    {
//        $this->test();
        return DB::table('version_participants')
            ->join('versions', 'versions.id', '=', 'version_participants.version_id')
            ->join('events', 'events.id', '=', 'versions.event_id')
            ->where('version_participants.user_id', auth()->id())
            ->where('versions.status', 'closed')
            ->select('version_participants.version_id AS id',
                'events.short_name AS eventName',
                'versions.short_name AS versionName', 'versions.status')
            ->orderByDesc('versions.senior_class_of')
            ->get()
            ->toArray();
    }

    private function test(): void
    {
        dd(DB::table('version_participants')
            ->join('versions', 'versions.id', '=', 'version_participants.version_id')
            ->join('events', 'events.id', '=', 'versions.event_id')
            ->where('version_participants.user_id', auth()->id())
            ->where('versions.status', 'closed')
            ->select('version_participants.id AS vpId'
//                'events.short_name AS eventName',
//                'versions.short_name AS versionName', 'versions.status'
            )
            ->get()
            ->toArray());
    }
}
