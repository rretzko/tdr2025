<?php

namespace App\Livewire\Events\Versions\Adjudications;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\Judge;
use App\Models\PhoneNumber;


class AdjudicationComponent extends BasePage
{
    public Room $room;
    public array $staff = [];
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();
        $this->versionId = $this->dto['versionId'];

        $this->room = $this->getRoom();
        $this->staff = $this->getStaff();
    }

    private function getRoom(): Room
    {
        $judge = Judge::query()
            ->where('user_id', auth()->id())
            ->where('version_id', $this->versionId)
            ->first();

        return Room::find($judge->room_id);
    }

    private function getStaff(): array
    {
        $staff = [];
        foreach ($this->room->judges()->with('user')->orderBy('judge_type')->get() as $judge) {
            $staff[] = [
                'name' => $judge['user']->name,
                'role' => $judge->judge_type,
                'email' => $judge['user']->email,
                'mobile' => $this->getMobilePhone($judge->user_id)
            ];
        }

        return $staff;
    }

    private function getMobilePhone(int $userId): string
    {
        return PhoneNumber::query()
            ->where('user_id', $userId)
            ->where('phone_type', 'mobile')
            ->value('phone_number') ?? 'cell not found';
    }

    public function render()
    {
        return view('livewire..events.versions.adjudications.adjudication-component');
    }

}
