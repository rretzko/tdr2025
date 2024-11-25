<?php

namespace App\Data\Pdfs;

use App\Models\Events\EventEnsemble;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Students\VoicePart;
use App\Services\ScoringRosterDataRowsService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PdfScoringRosterDataFactory
{
    private array $categoryColspans = [];
    private array $dto = [];
    private int $judgeCount = 0;
    private array $judges = [];
    private Collection $factors;
    private bool $scoresAscending = true;
    private Version $version;
    private VersionConfigAdjudication $versionConfigAdjudication;
    private int $voicePartId = 0;
    private array $voicePartIds = [];
    private Collection $voiceParts;

    public function __construct(
        private int $versionId,
        private readonly VoicePart|null|array $voicePart = null,
        private int $eventEnsembleId = 0
    ) {
        $this->version = Version::find($this->versionId);
        $this->dto['versionName'] = $this->version->name;
        $this->dto['categoryColspans'] = $this->version->scoreCategoriesWithColSpanArray;
        $this->dto['factors'] = $this->version->scoreFactors;
        $this->voiceParts = $this->version->event->voiceParts;
        $this->voicePartIds = $this->getVoicePartIds();
        $this->dto['voicePartDescr'] = is_null($voicePart) ? 'All Voices' : $voicePart->descr;

        $this->versionConfigAdjudication = VersionConfigAdjudication::where('version_id', $this->versionId)->first();
        $this->scoresAscending = $this->versionConfigAdjudication->scores_ascending;
        $this->dto['judgeCount'] = $this->versionConfigAdjudication->judge_per_room_count;

        if (!is_null($this->voicePart)) {
            $this->voicePartId = $this->voicePart->id;
        }

        $this->init();
    }

    public function getDto(): array
    {
        return $this->dto;
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function init(): void
    {
        $this->getRows();
    }

    private function getRows(): void
    {
        $voicePartIds = $this->voicePartId ? [$this->voicePartId] : $this->voicePartIds;

        $service = new ScoringRosterDataRowsService($this->versionId, $this->voicePartIds);
        $this->dto['rows'][$this->voicePartId] = $service->getRows();
    }

    private function getVoicePartIds(): array
    {
        if (is_a($this->voicePart, 'App\Models\Students\VoicePart')) {
            return [$this->voicePart->id];
        }

        if ($this->eventEnsembleId) {
            $eventEnsemble = EventEnsemble::find($this->eventEnsembleId);

            return explode(',', $eventEnsemble->voice_part_ids);
        }

        return $this->voiceParts->pluck('id')->toArray();
    }


}
