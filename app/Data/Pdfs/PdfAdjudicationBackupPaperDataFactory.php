<?php

namespace App\Data\Pdfs;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\Room;
use App\Models\UserConfig;
use Illuminate\Support\Collection;

class PdfAdjudicationBackupPaperDataFactory
{
    private array $dto = [];
    private array $rooms;

    public function __construct(private int $roomId)
    {
        $this->init();
    }

    private function init(): void
    {
        $this->rooms = $this->getRooms();

        $this->dto['rooms'] = $this->rooms;
        $this->dto['versionName'] = $this->getVersionName();
    }

    private function getRoomData(Room $room): array
    {
        return [
            'id' => $room->id,
            'roomName' => $room->room_name,
            'tolerance' => $room->tolerance,
        ];
    }

    private function getRooms(): array
    {
        $roomData = [];

        //return single object within a model for consistency
        if ($this->roomId) {
            $room = Room::find($this->roomId);
            $roomData[$this->roomId] = $this->getRoomData($room);

        } else {// return all room objects belonging to a $versionId

            $versionId = UserConfig::getValue('versionId');
            $rooms = Room::where('version_id', $versionId)->get();
            foreach ($rooms as $room) {
                $roomData[$room->id] = $this->getRoomData($room);
            }
        }

        return $roomData;
    }

    private function getVersionName(): string
    {
        return Version::find(UserConfig::getValue('versionId'))->name;

    }

    public function getDto(): array
    {
        return $this->dto;
    }
}
