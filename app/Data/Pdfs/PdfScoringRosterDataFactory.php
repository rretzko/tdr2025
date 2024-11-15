<?php

namespace App\Data\Pdfs;

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
    private Collection $voiceParts;

    public function __construct(private int $versionId, private readonly VoicePart|null $voicePart = null)
    {
        $this->version = Version::find($this->versionId);
        $this->dto['categoryColspans'] = $this->version->scoreCategoriesWithColSpanArray;
        $this->dto['factors'] = $this->version->scoreFactors;
        $this->voiceParts = $this->version->event->voiceParts;;

        $this->versionConfigAdjudication = VersionConfigAdjudication::where('version_id', $this->versionId)->first();
        $this->scoresAscending = $this->versionConfigAdjudication->scores_ascending;
        $this->dto['judgeCount'] = $this->versionConfigAdjudication->judge_per_room_count;

        if (!is_null($this->voicePart)) {
            $this->voicePartId = $this->voicePart->id;
        }

        $this->init();
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function init(): void
    {
        (is_null($this->voicePart))
            ? $this->buildAllVoicesPdf()
            : $this->buildSingleVoicePdf();
    }

    private function buildAllVoicesPdf()
    {
        foreach ($this->voiceParts as $voicePart) {

            $this->voicePartId = $voicePart->id;
            $this->getRows();
        }
    }

    private function getRows(): void
    {
        $service = new ScoringRosterDataRowsService($this->versionId, $this->voicePartId);
        $this->dto['rows'][$this->voicePartId] = $service->getRows();
    }

    private function buildSingleVoicePdf()
    {
        $this->getRows();
    }

    public function getDto(): array
    {
        return $this->dto;
    }
}
