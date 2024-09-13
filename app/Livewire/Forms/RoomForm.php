<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RoomForm extends Form
{
    // ROOM VARs
    public array $contentTypeIds = [];
    public int $orderBy = 1;
    public string $roomName = '';
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

    public function updateRoom(): bool
    {
        return false;
    }

    public function addRoom(): bool
    {
        return false;
    }

}
