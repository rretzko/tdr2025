<?php

namespace App\Livewire\Forms;

use App\Models\Events\Versions\VersionScoring;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VersionScoringForm extends Form
{
    #[Validate('required|string')]
    public string $abbr = '';
    #[Validate('required|int')]
    public int $best = 1;
    #[Validate('required|string')]
    public string $fileType = '';
    #[Validate('required|int')]
    public int $orderBy = 1;
    #[Validate('required|int')]
    public int $multiplier = 1;
    #[Validate('required|string|min:3')]
    public string $segment = '';
    public string $sysId = 'new';
    #[Validate('required|int')]
    public int $tolerance = 0;
    public int $versionId = 0;
    #[Validate('required|int')]
    public int $worst = 1;

    public function add(): void
    {
        $this->validate();

        VersionScoring::create(
            [
                'abbr' => $this->abbr,
                'best' => $this->best,
                'file_type' => $this->fileType,
                'order_by' => $this->orderBy,
                'multiplier' => $this->multiplier,
                'segment' => $this->segment,
                'tolerance' => $this->tolerance,
                'version_id' => $this->versionId,
                'worst' => $this->worst,
            ]
        );

        $this->resetAll();
    }

    private function resetAll()
    {
        $this->reset('abbr', 'segment', 'sysId');

        $this->orderBy++;
    }

    public function segmentUpdate(): void
    {
        $this->validate();

        VersionScoring::find($this->sysId)
            ->update(
                [
                    'abbr' => $this->abbr,
                    'best' => $this->best,
                    'file_type' => $this->fileType,
                    'order_by' => $this->orderBy,
                    'multiplier' => $this->multiplier,
                    'segment' => $this->segment,
                    'tolerance' => $this->tolerance,
                    'version_id' => $this->versionId,
                    'worst' => $this->worst,
                ]
            );

        $this->resetAll();
    }

    public function setDefaults(int $versionId, string $fileType): void
    {
        $this->fileType = $fileType;

        $this->versionId = $versionId;
    }

    public function setEditValues(int $versionScoringId): void
    {
        $vs = VersionScoring::find($versionScoringId);

        $this->sysId = $vs->id;

        $this->abbr = $vs->abbr;
        $this->best = $vs->best;
        $this->fileType = $vs->file_type;
        $this->multiplier = $vs->multiplier;
        $this->orderBy = $vs->order_by;
        $this->segment = $vs->segment;
        $this->tolerance = $vs->tolerance;
        $this->worst = $vs->worst;
    }

}
