<?php

namespace App\Services;

use App\Models\Events\Event;
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
        $eventId = $this->eventEnsembles->first()->event_id;
        $event = Event::find($eventId);
        $this->voiceParts = $event->voice_parts;

        $this->init();
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function init(): void
    {
        foreach ($this->eventEnsembles as $ensemble) {

            foreach ($this->voiceParts as $voicePart) {

                $this->buildCounts($voicePart->id, $ensemble);
            }

            $total = array_sum($this->counts[$ensemble->abbr]);
            $this->counts[$ensemble->abbr]['total'] = $total;
        }

    }

    private function buildCounts(int $voicePartId, EventEnsemble $ensemble): void
    {
        $abbr = $ensemble->abbr;
        $ensembleVoiceParts = $ensemble->voiceParts;

        if ($ensemble->voiceParts->where('id', $voicePartId)->first()) {

            $this->counts[$abbr][$voicePartId] = AuditionResult::query()
                ->where('version_id', $this->versionId)
                ->where('voice_part_id', $voicePartId)
                ->where('accepted', 1) //safeguard
                ->where('acceptance_abbr', $abbr)
                ->count('id');
        } else {

            $this->counts[$abbr][$voicePartId] = '-';
        }
    }

    public function getCounts(): array
    {
        return $this->counts;
    }
}
