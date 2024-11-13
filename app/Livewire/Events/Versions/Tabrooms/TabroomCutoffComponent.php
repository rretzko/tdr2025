<?php

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Livewire\BasePage;
use App\Models\AuditionResults\Factory;
use App\Models\Events\Event;
use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\UserConfig;
use App\Services\AuditionResultsScoreColorsService;
use App\Services\EventEnsembleSummaryCountService;
use App\Services\MaxScoreCountService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class TabroomCutoffComponent extends BasePage
{
    public Factory $factory;
    public Collection $eventEnsembles;
    public array $ensemblesArray;
    public Event $event;
    public array $scoresByVoicePart = [];
    public Version $version;
    public VersionConfigAdjudication $versionConfigAdjudication;
    public int $versionId = 0;
    public array $voicePartAbbrs = [];

    public function mount(): void
    {
        parent::mount();

        $this->versionId = UserConfig::getValue('versionId');
        $this->version = Version::find($this->versionId);

        $this->versionConfigAdjudication = VersionConfigAdjudication::where('version_id', $this->versionId)
            ->first();
        $this->event = $this->version->event;
        $this->eventEnsembles = $this->event->eventEnsembles;
        $this->ensemblesArray = $this->eventEnsembles->select('id', 'ensemble_name', 'abbr')->toArray();
        $this->voicePartAbbrs = $this->event->voiceParts->pluck('abbr')->toArray();

        $this->factory = new Factory();
    }

    public function render()
    {
        return view('livewire..events.versions.tabrooms.tabroom-cutoff-component',
            [
                'scores' => $this->getScores(),
                'ensembleSummaryCounts' => $this->getEnsembleSummaryCounts(),
            ]);
    }

    public function clickScore($score, $voicePartId): void
    {
        //register score and update auditionResults
        $this->factory->setScore(
            $this->eventEnsembles,
            $this->versionConfigAdjudication,
            $score,
            $voicePartId);
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function getEnsembleSummaryCounts(): array
    {
        $service = new EventEnsembleSummaryCountService($this->eventEnsembles, $this->versionId);

        return $service->getCounts();
    }

    private function getScores(): array
    {
        $scores = [];

        foreach ($this->event->voiceParts as $voicePart) {

            $scores[] = [
                'colHeader' => $voicePart->abbr,
                'voicePartId' => $voicePart->id,
                'scores' => $this->getScoresByVoicepart($voicePart->id),
            ];
        }

        return $scores;
    }

    private function getScoresByVoicePart(int $voicePartId): array
    {
        $sortAscending = (bool) $this->versionConfigAdjudication->scores_ascending;
        $maxScoreCount = MaxScoreCountService::getMaxScoreCount($this->versionConfigAdjudication);

        $query = AuditionResult::query()
            ->where('voice_part_id', $voicePartId)
            ->where('score_count', $maxScoreCount)
            ->where('version_id', '=', $this->versionId);

        if ($sortAscending) {
            $query->orderBy('total');
        } else {
            $query->orderByDesc('total');
        }

        $scores = $query
            ->pluck('total')
            ->toArray();

        $service = new AuditionResultsScoreColorsService($scores, $this->versionConfigAdjudication, $voicePartId,
            $this->eventEnsembles);

        return $service->getColors($scores);
    }

    /** END OF PUBLIC FUNCTIONS  *************************************************/

    private function getMaxScoreCount(): int
    {
        $scoreFactor = new ScoreFactor();
        $scoreFactorCount = $scoreFactor->getCountByVersionId($this->versionId);
        $judgeCount = $this->versionConfigAdjudication->judge_per_room_count;

        return ($scoreFactorCount * $judgeCount);

    }

}
