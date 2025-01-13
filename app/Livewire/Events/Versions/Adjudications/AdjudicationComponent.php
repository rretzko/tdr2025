<?php

namespace App\Livewire\Events\Versions\Adjudications;

use App\Events\UpdateAuditionResultsEvent;
use App\Livewire\BasePage;
use App\Livewire\Forms\AdjudicationForm;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\Judge;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigDate;
use App\Models\Events\Versions\VersionPitchFile;
use App\Models\PhoneNumber;
use App\Models\Students\VoicePart;
use App\Services\SetAveragedScoresService;
use App\Services\UpdateAuditionResultsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;
use LaravelIdea\Helper\App\Models\Events\Versions\_IH_VersionPitchFile_C;


class AdjudicationComponent extends BasePage
{
    public AdjudicationForm $form;
    public string $auditionDeadline = '';
    public int $countCompleted = 0;
    public int $countError = 0;
    public int $countPending = 0;
    public int $countWip = 0;
    public bool $hasRecording = false;
    public string $nextRef = '';
    public int $pctCompleted = 50;
    public int $pctError = 20;
    public int $pctPending = 10;
    public int $pctWip = 20;
    public array $referenceMaterials = [];
    public Room $room;
    public string $scoreUpdatedMssg = '';
    public bool $showAllButtons = true;
    public bool $showProgressBar = true;
    public bool $showStaff = false;
    public array $staff = [];
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();
        $this->versionId = $this->dto['versionId'];
        $adjudicationClose = VersionConfigDate::query()
            ->where('version_id', $this->versionId)
            ->where('date_type', 'adjudication_close')
            ->first()
            ->version_date;
        $this->auditionDeadline = Carbon::parse($adjudicationClose)->format('D, M d, Y @ g:i:s a');

        $this->room = $this->getRoom();
        $this->staff = $this->getStaff();

        $this->showStaff = ($this->firstTimer !== 'false');

        $recordings = ['audio', 'video'];
        $this->hasRecording = in_array(Version::find($this->versionId)->upload_type, $recordings);

        $this->referenceMaterials = $this->getReferenceMaterials();
    }

    public function render()
    {
        $this->setProgressBarCounts();

        return view('livewire..events.versions.adjudications.adjudication-component',
            [
                'rows' => $this->getRows(),
            ]);
    }

    #[NoReturn] public function clickJudgeScoresToggle(): void
    {
        $this->form->hasMyScores = !$this->form->hasMyScores;
    }

    #[NoReturn] public function clickNextAudition(int $prevCandidateId): void
    {
        $buttons = $this->getRows();
        foreach ($buttons as $key => $button) {
            if ($button->id == $prevCandidateId) {

                $nextKey = ($key + 1);
                while (array_key_exists($nextKey, $buttons) && $buttons[$nextKey]->scoringCompleted) {
                    $nextKey++;
                }

                $nextId = $buttons[0]->id; //default
                if (array_key_exists($nextKey, $buttons)) {
                    $nextId = $buttons[$nextKey]->id;
                    $this->setNextRef($prevCandidateId);

                    $this->clickRef($nextId);
                    break;
                }
            }
        }
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

        $this->setNextRef($candidateId, 1);

    }

    public function save(): void
    {
        $this->reset('scoreUpdatedMssg');

        $judgeOrderBys = $this->calcJudgeOrderBys();

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

        new SetAveragedScoresService($this->form->room, $this->form->candidate);

        $this->form->roomScores = $this->form->getRoomScores();

        $this->form->setScoreTolerance();

        $this->form->setHasMyScores();

        event(new UpdateAuditionResultsEvent($this->form->candidate));

        $this->scoreUpdatedMssg = 'Last update: '.Carbon::now('America/New_York')->format('D, M d @ g:i:s a');
    }

    public function updatedFormScores($value, $key)
    {
        $scoreCount = $this->form->updateScores($value, $key);
    }

    private function calcJudgeOrderBys(): array
    {
        $judges = [];
        $judgeTypes = ['head judge', 'judge 2', 'judge 3', 'judge 4', 'judge monitor'];

        foreach ($judgeTypes as $judgeType) {
            if (!is_null($this->room->judges->where('judge_type', $judgeType)->first())) {
                $judges[$judgeType] = (count($judges) + 1);
            }
        }

        return $judges;
    }

    private function getNextRef(int $candidateId): string
    {
        return $this->nextRef;
    }

    private function getReferenceMaterials(): array
    {
        $vpf = VersionPitchFile::query()
            ->where('version_id', $this->versionId)
            ->where('file_type', 'pdf')
            ->get();

        $materials = [];

        foreach ($vpf as $pdf) {
            $materials[] =
                [
                    'descr' => $pdf->description,
                    'url' => $pdf->url,
                ];
        }

        return $materials;
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

    /**
     * return the 'ref' value of the next button UNLESS the next button is in 'completed' state.
     *  - (scoringCompleted === 'completed')
     * if in 'completed' state, continue  to advance through the $buttons array until a non-completed state is
     * found or the array ends.
     * @param  int  $candidateId
     * @param  int  $interval
     * @return void
     */
    private function setNextRef(int $candidateId): void
    {
        $buttons = $this->getRows();
        //iterate through the buttons array until the target $candidatedId is found
        foreach ($buttons as $key => $button) {

            if ($button->id == $candidateId) {

                //calculate the next key in the array
                $nextRefKey = ($key + 1);

                //if the adjudication has already been completed by this judge, move to the next button
                //if the end of the array is reached, stop the iteration
                while (array_key_exists($nextRefKey, $buttons) && ($buttons[$nextRefKey]->scoringCompleted)) {
                    $nextRefKey++;
                }

                //if not at the end of the array, assign the next bullet's ref to $this->nextRef
                if (array_key_exists($nextRefKey, $buttons)) {
                    $this->nextRef = $buttons[$nextRefKey]->ref;
                    break;
                } else {
                    $this->nextRef = 'none';
                }

            } else {
                $this->nextRef = 'none';
            }
        }
    }

    private function setProgressBarCounts(): void
    {
        $registeredCount = $this->room->getCountRegistrants();
        $this->countError = $this->room->getCountError();
        $this->countCompleted = $this->room->getCountCompleted();
        $this->countWip = $this->room->getCountWip();
        $this->countPending = ($registeredCount - ($this->countCompleted + $this->countWip + $this->countError));

        $this->pctError = $this->calculatePercentage($this->countError, $registeredCount);
        $this->pctCompleted = $this->calculatePercentage($this->countCompleted, $registeredCount);
        $this->pctPending = $this->calculatePercentage($this->countPending, $registeredCount);
        $this->pctWip = $this->calculatePercentage($this->countWip, $registeredCount);

    }

    private function calculatePercentage(int $count, int $total): int
    {
        return $total ? floor(($count / $total) * 100) : 0;
    }

}
