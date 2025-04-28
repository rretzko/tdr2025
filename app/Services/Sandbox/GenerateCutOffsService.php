<?php

namespace App\Services\Sandbox;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;

class GenerateCutOffsService
{
    private bool $alternatingScores = false;
    private int $counter = 0;

    public function __construct(private readonly int $versionId)
    {
        $vca = VersionConfigAdjudication::where('version_id', $this->versionId)->first();
        $this->alternatingScores = $vca->alternating_scores;

        $this->init();
    }

    private function init(): void
    {
        $this->counter = 0;
    }

    public function getCounter(): int
    {
        return $this->counter;
    }
}
