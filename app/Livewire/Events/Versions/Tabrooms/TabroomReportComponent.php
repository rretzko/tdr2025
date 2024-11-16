<?php

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\UserConfig;
use App\Services\ScoringRosterDataRowsService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;

class TabroomReportComponent extends BasePage
{
    public array $categories = [];
    public string $displayReportData = '';
    public bool $displayReport = false;
    public Collection $factors;
    public int $judgeCount;
    public bool $scoresAscending = true;
    public int $versionId = 0;
    public int $voicePartId = 0;
    public array $voicePartIds = [];
    public Collection $voiceParts;

    public function mount(): void
    {
        parent::mount();

        $this->versionId = UserConfig::getValue('versionId');
        $versionConfigAdjudication = VersionConfigAdjudication::where('version_id', $this->versionId)->first();
        $this->judgeCount = $versionConfigAdjudication->judge_per_room_count;
        $this->scoresAscending = $versionConfigAdjudication->scores_ascending;
        $this->categories = $this->getCategories();
        $this->factors = $this->getFactorAbbrs();
        $this->voiceParts = $this->getVoiceParts();
        $this->voicePartIds = $this->voiceParts->pluck('id')->toArray();
    }

    public function render()
    {
        return view('livewire..events.versions.tabrooms.tabroom-report-component',
            [
                'rows' => $this->getRows(),
            ]);
    }

    #[NoReturn] public function clickButton(string $type): void
    {
        $this->reset('voicePartId');

        if ($type === 'byVoicePart') {
            $this->voicePartId = $this->voiceParts->first()->id;
        }

        $this->displayReport = !$this->displayReport;
        $this->displayReportData = $type;
    }

    #[NoReturn] public function clickPrinter()
    {
        $uri = '/versions/tabroom/reports/'.$this->displayReportData;

        if ($this->voicePartId) {
            $uri .= '/'.$this->voicePartId;
        }

        if ($this->displayReportData === 'allPrivate') {
            $uri .= '/74/1';
        }

        return $this->redirect($uri);
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function getCategories(): array
    {
        $version = Version::find($this->versionId);

        return $version->scoreCategoriesWithColSpanArray;
    }

    private function getFactorAbbrs(): Collection
    {
        $version = Version::find($this->versionId);

        return $version->scoreFactors;
    }

    private function getRows(): array
    {
        $voicePartIds = $this->voicePartId ? [$this->voicePartId] : $this->voicePartIds;

        $service = new ScoringRosterDataRowsService($this->versionId, $voicePartIds);

        return $service->getRows();
    }

    private function getScores(array &$candidates): void
    {
        foreach ($candidates as $candidate) {

            for ($i = 1; $i <= $this->judgeCount; $i++) {

                foreach ($this->factors as $factor) {

                    $candidate->scores[] = Score::query()
                        ->where('candidate_id', $candidate->id)
                        ->where('judge_order_by', $i)
                        ->where('score_factor_order_by', $factor->order_by)
                        ->select('score')
                        ->value('score') ?? 0;
                }
            }
        }
    }

    private function getVoiceParts(): Collection
    {
        $versionId = UserConfig::getValue('versionId');
        return Version::find($versionId)->event->voiceParts;
    }
}
