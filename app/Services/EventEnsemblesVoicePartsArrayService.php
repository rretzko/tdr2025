<?php

namespace App\Services;

use App\Models\Students\VoicePart;
use Illuminate\Database\Eloquent\Collection;

class EventEnsemblesVoicePartsArrayService
{
    private array $voiceParts = [];

    public function __construct(private readonly Collection $eventEnsembles)
    {
        $this->init();
    }

    private function init(): void
    {
        $ensembles = $this->eventEnsembles;

        $voiceParts = $ensembles->flatMap(function ($ensemble) {
            return explode(',', $ensemble->voice_part_ids);
        })->unique();

        $this->voiceParts = VoicePart::query()
            ->whereIn('id', $voiceParts)
            ->whereNot('descr', 'ALL')
            ->orderBy('order_by')
            ->pluck('descr', 'id')
            ->toArray();
    }

    public function getArray()
    {
        return $this->voiceParts;
    }
}
