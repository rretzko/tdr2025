<?php

namespace App\Livewire\Events\Versions\Adjudications;

use App\Livewire\BasePage;
use App\Livewire\Forms\AdjudicationForm;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\Judge;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\PhoneNumber;
use App\Models\Students\VoicePart;
use Illuminate\Support\Facades\Log;


class AdjudicationComponent extends BasePage
{
    public AdjudicationForm $form;
    public int $countCompleted = 50;
    public int $countError = 20;
    public int $countPending = 10;
    public int $countWip = 20;
    public bool $hasRecording = false;
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

        $recordings = ['audio', 'video'];
        $this->hasRecording = in_array(Version::find($this->versionId)->upload_type, $recordings);
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

    public function save(): void
    {
        $judgeOrderBys = [
            'head judge' => 1,
            'judge 2' => 2,
            'judge 3' => 3,
            'judge 4' => 4,
            'judge monitor' => 5,
        ];

        foreach ($this->form->scores as $factorId => $score) {

            $scoreFactor = ScoreFactor::find($factorId);
            $scoreCategory = ScoreCategory::find($scoreFactor->score_category_id);
            $judge = $this->room->judges()->where('user_id', auth()->id())->first();
            $voicePart = VoicePart::find($this->form->candidate->voice_part_id);

            Score::updateOrCreate(
                [
                    'version_id' => $this->versionId,
                    'candidate_id' => $this->form->sysId,
                    'student_id' => $this->form->candidate->student_id,
                    'school_id' => $this->form->candidate->school_id,
                    'score_category_id' => $scoreCategory->id,
                    'score_category_order_by' => $scoreCategory->order_by,
                    'score_factor_id' => $factorId,
                    'score_factor_order_by' => $scoreFactor->order_by,
                    'judge_id' => $judge->id,
                    'judge_order_by' => $judgeOrderBys[$judge->judge_type],
                    'voice_part_id' => $voicePart->id,
                    'voice_part_order_by' => $voicePart->order_by,
                ],
                [
                    'score' => $score,
                ]
            );
        }

        $this->form->roomScores = $this->form->getRoomScores();
    }

    public function updatedFormScores($value, $key)
    {
        $scoreCount = $this->form->updateScores($value, $key);
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
