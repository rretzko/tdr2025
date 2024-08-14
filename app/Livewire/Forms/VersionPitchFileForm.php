<?php

namespace App\Livewire\Forms;

use App\Models\Events\Versions\VersionPitchFile;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VersionPitchFileForm extends Form
{
    #[Validate('required|string|min:3')]
    public string $description = '';
    #[Validate('required|string|min:3')]
    public string $fileType = '';
    #[Validate('required|int|min:1')]
    public int $orderBy = 1;
    #[Validate('nullable|int|exists:version_pitch_files,id')]
    public int $previousPitchFileId = 0;
    public string $sysId = 'new';
    public int $versionId = 0;
    #[Validate('required', message: 'A file must be selected.')]
    public string $url = '';
    #[Validate('required|int|min:0|exists:voice_parts,id')]
    public int $voicePartId = 0;

    public function add(): void
    {
        $this->validate();

        if ($this->previousPitchFileId) {

            dd($this->previousPitchFileId);

        } else {
            VersionPitchFile::create(
                [
                    'description' => $this->description,
                    'file_type' => $this->fileType,
                    'order_by' => $this->orderBy,
                    'version_id' => $this->versionId,
                    'voice_part_id' => $this->voicePartId,
                    'url' => $this->url,
                ]
            );
        }
    }

    public function pitchFileUpdate(): void
    {
        $this->validate();

        VersionPitchFile::find($this->sysId)
            ->update(
                [
                    'description' => $this->description,
                    'file_type' => $this->fileType,
                    'order_by' => $this->orderBy,
                    'version_id' => $this->versionId,
                    'voice_part_id' => $this->voicePartId,
                    'url' => $this->url,
                ]
            );
    }

    public function resetAll(): void
    {
        $this->reset('description', 'fileType', 'orderBy',
            'ulr', 'versionId', 'voicePartId');
    }

    public function setDefaults(int $versionId, array $voiceParts): void
    {
        $this->versionId = $versionId;

        $this->voicePartId = array_key_first($voiceParts);
    }

    public function setNewPitchFile(int $versionId, array $fileTypes, array $voiceParts): void
    {
        $this->resetAll();

        $this->versionId = $versionId;

        $this->fileType = array_key_first($fileTypes);

        $this->voicePartId = array_key_first($voiceParts);
    }

    public function setPitchFile(int $versionId, int $versionPitchFileId): void
    {
        $this->resetAll();

        $this->versionId = $versionId;

        $vpf = VersionPitchFile::find($versionPitchFileId);

        $this->sysId = $vpf->id;
        $this->description = $vpf->description;
        $this->fileType = $vpf->file_type;
        $this->orderBy = $vpf->order_by;
        $this->versionId = $vpf->version_id;
        $this->voicePartId = $vpf->voice_part_id;
        $this->url = $vpf->url;
    }
}
