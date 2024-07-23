<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class VersionPitchFileForm extends Form
{
    public string $description = '';
    public string $fileType = '';
    public int $orderBy = 1;
    public int $versionId = 0;
    public string $url = '';
    public int $voicePartId = 0;

    public function setNewPitchFile(int $versionId): void
    {
        $this->versionId = $versionId;

        $this->reset('description', 'fileType', 'orderBy',
            'ulr', 'voicePartId');
    }
}
