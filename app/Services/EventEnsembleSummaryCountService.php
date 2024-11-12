<?php

namespace App\Services;

use App\Models\Events\EventEnsemble;
use App\Models\Events\Versions\Participations\AuditionResult;
use Illuminate\Database\Eloquent\Collection;

class EventEnsembleSummaryCountService
{
    private string $eventEnsembleAbbr = '';
    private array $counts;
    private $voiceParts;

    public function __construct(private readonly Collection $eventEnsembles, private int $versionId)
    {
        $this->voiceParts = $this->eventEnsembles->first()->voice_parts;

        $this->init();
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function init(): void
    {
        foreach ($this->eventEnsembles as $ensemble) {

            foreach ($this->voiceParts as $voicePart) {

                $this->buildCounts($voicePart->id, $ensemble->abbr);
            }

            $total = array_sum($this->counts[$ensemble->abbr]);
            $this->counts[$ensemble->abbr]['total'] = $total;
        }

    }

    private function buildCounts(int $voicePartId, string $abbr): void
    {
        $this->counts[$abbr][$voicePartId] = AuditionResult::query()
            ->where('version_id', $this->versionId)
            ->where('voice_part_id', $voicePartId)
            ->where('accepted', 1) //safeguard
            ->where('acceptance_abbr', $abbr)
            ->count('id');
    }

    public function getCounts(): array
    {
        return $this->counts;
    }
}
