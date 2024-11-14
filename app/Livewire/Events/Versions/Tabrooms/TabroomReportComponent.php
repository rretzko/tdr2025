<?php

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\UserConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\NoReturn;

class TabroomReportComponent extends BasePage
{
    public string $displayReportData = '';
    public bool $displayReport = false;
    public Collection $factors;
    public int $judgeCount;
    public bool $scoresAscending = true;
    public int $versionId = 0;
    public int $voicePartId = 0;
    public Collection $voiceParts;

    public function mount(): void
    {
        parent::mount();

        $this->versionId = UserConfig::getValue('versionId');
        $versionConfigAdjudication = VersionConfigAdjudication::where('version_id', $this->versionId)->first();
        $this->judgeCount = $versionConfigAdjudication->judge_per_room_count;
        $this->scoresAscending = $versionConfigAdjudication->scores_ascending;
        $this->factors = $this->getFactorAbbrs();
        $this->voiceParts = $this->getVoiceParts();
        $this->voicePartId = $this->voiceParts->first()->id;
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function getVoiceParts(): Collection
    {
        $versionId = UserConfig::getValue('versionId');
        return Version::find($versionId)->event->voiceParts;
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
        $this->displayReport = !$this->displayReport;
        $this->displayReportData = $type;
    }

    public function clickPrinter(): void
    {
        dd(__METHOD__);
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function getFactorAbbrs(): Collection
    {
        $version = Version::find($this->versionId);

        return $version->scoreFactors;
    }

    private function getRows(): array
    {
        $candidates = DB::table('candidates')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->leftJoin('audition_results', 'audition_results.candidate_id', '=', 'candidates.id')
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->where('candidates.voice_part_id', $this->voicePartId)
            ->distinct()
            ->select('candidates.id',
                'voice_parts.abbr AS voicePartAbbr', 'voice_parts.order_by AS voicePartOrderBy',
                'audition_results.total', 'audition_results.accepted', 'audition_results.acceptance_abbr',
            )
            ->orderBy('voicePartOrderBy')
            ->orderBy('audition_results.total', ($this->scoresAscending ? 'asc' : 'desc'))
            ->orderBy('candidates.id')
            ->get()
            ->toArray();

        $this->getScores($candidates);
//dd($candidates);
        return $candidates;
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
}
