<?php

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Registrant;
use App\Models\Events\Versions\Room;
use App\Models\UserConfig;

class TabroomTrackingComponent extends BasePage
{
    public int $versionId;

    public function mount(): void
    {
        parent::mount();

        $this->versionId = UserConfig::getValue('versionId');
    }

    public function render()
    {
        return view('livewire..events.versions.tabrooms.tabroom-tracking-component',
            [
                'rooms' => $this->getCandidatesByRoom(),
            ]);
    }

    private function getCandidatesByRoom(): array
    {
        $rooms = Room::query()
            ->where('rooms.version_id', $this->versionId)
            ->orderBy('rooms.order_by')
            ->get();

        $candidates = [];
        foreach ($rooms as $room) {
            $candidates[] = [
                'roomName' => $room->room_name,
                'candidates' => $room->registrantsByIdForTabroom,
            ];
        }

        return $candidates;
    }
}
