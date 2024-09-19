<?php

namespace App\Data\Pdfs;

use App\Models\Events\Versions\Room;
use App\Models\UserConfig;
use Illuminate\Support\Collection;

class PdfAdjudicationBackupPaperDataFactory
{
    private array $dto = [];
    private Collection $rooms;

    public function __construct(private int $roomId)
    {
        $this->rooms = $this->getRooms();
    }

    private function getRooms(): Collection
    {
        //return single object within a model for consistency
        if ($this->roomId) {
            return collect(Room::find($this->roomId));
        }

        //else return all room objects belonging to a $versionId
        $versionId = UserConfig::getValue('versionId');
        return Room::where('version_id', $versionId)->get();
    }

    public function getDto(): array
    {
        return $this->dto;
    }
}
