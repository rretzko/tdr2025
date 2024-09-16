<?php

namespace App\Livewire\Forms;

use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\RoomScoreCategory;
use App\Models\Events\Versions\Scoring\RoomVoicePart;
use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RoomForm extends Form
{
    // ROOM VARs
    public int $orderBy = 1;
    public string $roomName = '';
    public array $scoreCategoryIds = [];
    public int $sysId = 0;
    public int $tolerance = 0;
    public int $userId = 0;
    public int $versionId = 0;
    public array $voicePartIds = [];

    //JUDGE VARs
    public int $headJudge = 0;
    public int $judge2 = 0;
    public int $judge3 = 0;
    public int $judge4 = 0;
    public int $judgeMonitor = 0;
    public int $monitor = 0;

    public function save(): bool
    {
        return ($this->sysId)
            ? $this->updateRoom()
            : $this->addRoom();
    }

    public function setRoom(int $roomId): bool
    {
        $this->sysId = $roomId;
        $room = Room::find($this->sysId);
        $this->orderBy = $room->order_by;
        $this->roomName = $room->room_name;
        $this->tolerance = $room->tolerance;

        $this->scoreCategoryIds = $this->getScoreCategoryIds();

        $this->voicePartIds = RoomVoicePart::query()
            ->where('room_id', $this->sysId)
            ->pluck('voice_part_id')
            ->toArray();

        return true;
    }

    public function updateRoom(): bool
    {
        $room = Room::find($this->sysId);

        $room->update(
            [
                'room_name' => $this->roomName,
                'tolerance' => $this->tolerance,
                'order_by' => $this->orderBy,
            ]
        );

        $this->updateRoomScoreCategories($room);

        $this->updateRoomVoiceParts($room);

        return true;
    }

    public function updateRoomScoreCategories(Room $room): void
    {
        //delete all current score categories for $room
        RoomScoreCategory::query()
            ->where('room_id', $room->id)
            ->delete();

        //add new score categories for $room
        foreach ($this->scoreCategoryIds as $scoreCategoryId) {

            RoomScoreCategory::create(
                [
                    'room_id' => $room->id,
                    'score_category_id' => $scoreCategoryId,
                ]
            );
        }
    }

    public function updateRoomVoiceParts(Room $room): void
    {
        //delete all current voice parts for $room
        RoomVoicePart::query()
            ->where('room_id', $room->id)
            ->delete();

        //add new voice parts for $room
        foreach ($this->voicePartIds as $voicePartId) {

            RoomVoicePart::create(
                [
                    'room_id' => $room->id,
                    'voice_part_id' => $voicePartId,
                ]
            );
        }
    }

    public function addRoom(): bool
    {
        return false;
    }

    private function getScoreCategoryIds(): array
    {
        return RoomScoreCategory::query()
            ->where('room_id', $this->sysId)
            ->pluck('score_category_id')
            ->toArray() ?? [];
    }

}
