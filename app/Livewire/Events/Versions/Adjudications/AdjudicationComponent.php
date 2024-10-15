<?php

namespace App\Livewire\Events\Versions\Adjudications;

use App\Livewire\BasePage;
use App\Livewire\Forms\AdjudicationForm;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\Judge;
use App\Models\PhoneNumber;


class AdjudicationComponent extends BasePage
{
    public AdjudicationForm $form;
    public int $countCompleted = 50;
    public int $countError = 20;
    public int $countPending = 10;
    public int $countWip = 20;
    public int $pctCompleted = 50;
    public int $pctError = 20;
    public int $pctPending = 10;
    public int $pctWip = 20;
    public Room $room;
    public bool $showAllButtons = true;
    public bool $showProgressBar = true;
    public bool $showStaff = false;
    public array $staff = [];
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();
        $this->versionId = $this->dto['versionId'];

        $this->room = $this->getRoom();
        $this->staff = $this->getStaff();

        $this->showStaff = ($this->firstTimer !== 'false');

    }

    public function render()
    {
        return view('livewire..events.versions.adjudications.adjudication-component',
            [
                'rows' => $this->getRows(),
            ]);
    }

    /**
     * Load scoring factors based on button click
     * @param  int  $candidateId
     * @return void
     */
    public function clickRef(int $candidateId): void
    {
        $judge = $this->room->judges()->where('user_id', auth()->id())->first();
        $this->form->setCandidate(Candidate::find($candidateId), $this->room, $judge);
    }

    public function updatedFormScores($value, $key)
    {
//        dd($value.': '.$key);
        $this->form->updateScores();
    }

    private function getRoom(): Room
    {
        $judge = Judge::query()
            ->where('user_id', auth()->id())
            ->where('version_id', $this->versionId)
            ->first();

        return Room::find($judge->room_id);
    }

    private function getRows(): array
    {
        return ($this->showAllButtons)
            ? $this->room->adjudicationButtonsAllArray
            : $this->room->adjudicationButtonsIncompleteArray;
    }

    private function getStaff(): array
    {
        $staff = [];
        foreach ($this->room->judges()->with('user')->orderBy('judge_type')->get() as $judge) {
            $staff[] = [
                'name' => $judge['user']->name,
                'role' => $judge->judge_type,
                'email' => $judge['user']->email,
                'mobile' => $this->getMobilePhone($judge->user_id)
            ];
        }

        return $staff;
    }

    private function getMobilePhone(int $userId): string
    {
        return PhoneNumber::query()
            ->where('user_id', $userId)
            ->where('phone_type', 'mobile')
            ->value('phone_number') ?? 'cell not found';
    }


}
