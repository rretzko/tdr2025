<?php

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Obligation;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\Judge;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Version;
use App\Services\Sandbox\GenerateScoresService;
use JetBrains\PhpStorm\NoReturn;

class TabroomSandboxComponent extends BasePage
{
    public int $candidateCount = 0;
    public int $eventId = 0;
    public int $judgeCount = 0;
    public int $participantCount = 0;
    public int $registrantCount = 0;
    public int $roomCount = 0;
    public int $scoreCount = 0;
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();
//dd(array_key_exists('versionId', $this->dto));
        if (array_key_exists('versionId', $this->dto) &&
            $this->dto['versionId']) {

            $this->versionId = $this->dto['versionId'];
            $version = Version::find($this->versionId);
            $this->eventId = $version->event_id;
            $this->participantCount = $this->getParticipantCount();

            if ($this->participantCount) {
                $this->candidateCount = $this->getCandidateCount();

                if ($this->candidateCount) {
                    $this->registrantCount = $this->getRegistrantCount();
                }
            }

            $this->roomCount = $this->getRoomCount();
            if ($this->roomCount) {
                $this->judgeCount = $this->getJudgeCount();

                if ($this->judgeCount) {
                    $this->scoreCount = $this->getScoreCount();
                }
            }
        }
    }

    private function getParticipantCount(): int
    {
        return Obligation::where('version_id', $this->versionId)->count();
    }

    private function getCandidateCount(): int
    {
        return Candidate::where('version_id', $this->versionId)->count();
    }

    private function getRegistrantCount(): int
    {
        return Candidate::query()
            ->where('version_id', $this->versionId)
            ->where('status', 'registered')
            ->count();
    }

    private function getRoomCount(): int
    {
        return Room::where('version_id', $this->versionId)->count();
    }

    private function getJudgeCount(): int
    {
        return Judge::where('version_id', $this->versionId)->count();
    }

    private function getScoreCount(): int
    {
        return Score::where('version_id', $this->versionId)->count();
    }

    public function render()
    {
        return view('livewire..events.versions.tabrooms.tabroom-sandbox-component');
    }

    #[NoReturn] public function pending(): void
    {
        dd('placeholder function: '.__METHOD__);
    }

    #[NoReturn] public function generateScores(): void
    {
        new GenerateScoresService($this->versionId);
    }
}
