<?php

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Livewire\BasePage;
use App\Models\Events\Event;
use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\UserConfig;

class TabroomCutoffComponent extends BasePage
{
    public array $ensembles;
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
        $this->ensembles = $this->event->eventEnsembles->select('id', 'ensemble_name')->toArray();
        $this->voicePartAbbrs = $this->event->voiceParts->pluck('abbr')->toArray();
    }

    public function render()
    {
        return view('livewire..events.versions.tabrooms.tabroom-cutoff-component',
            [
                'scores' => $this->getScores(),
            ]);
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
        $maxScoreCount = $this->getMaxScoreCount();

        $query = AuditionResult::query()
            ->where('voice_part_id', $voicePartId)
            ->where('score_count', $maxScoreCount);

        if ($sortAscending) {
            $query->orderBy('total');
        } else {
            $query->orderByDesc('total');
        }

        return $query
            ->pluck('total')
            ->toArray();
    }

    /** END OF PUBLIC FUNCTIONS  *************************************************/

    private function getMaxScoreCount(): int
    {
        $judgeCount = $this->versionConfigAdjudication->judge_per_room_count;

        //test for count based on versionId
        $scoreFactorCount = ScoreFactor::query()
            ->where('version_id', $this->versionId)
            ->count('id');

        //if not successful, use event->id
        if (!$scoreFactorCount) {
            $scoreFactorCount = ScoreFactor::query()
                ->where('event_id', $this->event->id)
                ->count('id');
        }

        return $judgeCount * $scoreFactorCount;
    }

    public function clickScore($score, $voicePartId)
    {
        //evaluate ensemble assignment
        //register cut-off score
        //assign accepted bool and acceptance_abbr to audition_results
        //highlight score values with background color coding (blue for first ensemble, yellow for second ensemble)
        //calculate ensemble count summaries
        //re-render
    }


}
