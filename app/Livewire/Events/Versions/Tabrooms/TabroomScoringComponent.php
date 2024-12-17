<?php

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Events\UpdateAuditionResultsEvent;
use App\Livewire\BasePage;
use App\Livewire\Forms\AdjudicationForm;
use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\Judge;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Students\VoicePart;
use App\Models\UserConfig;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TabroomScoringComponent extends BasePage
{
    public AdjudicationForm $form;
    public string $candidateError = '';
    public string $candidateId = '';
    public string $candidateName = '';
    public string $candidateRef = '';
    public Collection $candidates;
    public string $candidateSchool = '';
    public int $candidateScoreCount = 0;
    public string $candidateTeacher = '';
    public string $candidateVoicePartDescr = '';
    public Collection $eventVoiceParts;
    public bool $hasRecordings = false;
    public Judge $judge;
    public int $judgeId = 0;
    public Collection $judges;
    public string $lastName = '';
    public array $recordings = [];
    public Room $room;
    public int $roomId = 0;
    public Collection $rooms;
    public string $scoreUpdatedMssg = '';
    public int $selectedVoicePartId = 0;
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->candidates = collect();
        $this->versionId = UserConfig::getValue('versionId');
        $version = Version::find($this->versionId);
        $this->hasRecordings = (bool) ($version->upload_type !== 'none');

        $this->eventVoiceParts = $this->getEventVoiceParts();
    }

    public function render()
    {
        return view('livewire..events.versions.tabrooms.tabroom-scoring-component',
            [

            ]);
    }

    public function clickCandidateButton(int $candidateId): void
    {
        $this->candidateId = $candidateId;
        $this->reset('lastName');
        $this->updatedCandidateId();
    }

    public function clickChangeVoicePartId()
    {
        //remove audition results
        AuditionResult::where('candidate_id', $this->candidateId)->first()?->delete();

        //remove scores
        Score::where('candidate_id', $this->candidateId)->delete();

        //change voice part id
        $candidate = Candidate::find($this->candidateId);
        $candidate->voice_part_id = $this->selectedVoicePartId;
        $candidate->save();

        $this->updatedCandidateId();

        $this->roomId = $this->rooms->first()->id;
        $this->updatedRoomId();

//        $this->judgeId = $this->judges->first()->id;
    }


    public function updatedLastName(): void
    {
        $this->reset('candidateError', 'candidateId', 'candidates',
            'candidateName', 'candidateSchool', 'candidateTeacher', 'candidateVoicePartDescr');

        $minLength = 2;

        if (strlen($this->lastName) >= $minLength) {

            $this->candidates = Candidate::query()
                ->join('students', 'students.id', '=', 'candidates.student_id')
                ->join('users', 'users.id', '=', 'students.user_id')
                ->where('program_name', 'LIKE', '%'.$this->lastName.'%')
                ->where('candidates.version_id', $this->versionId)
                ->with('school', 'student', 'student.user', 'voicePart', 'teacher', 'teacher.user')
                ->select('candidates.id', 'users.name', 'users.last_name', 'users.first_name')
                ->orderBy('users.last_name')
                ->orderBy('users.first_name')
                ->get();

            if (!$this->candidates->count()) {

                $this->candidateError = "No candidate found with last name: ".$this->lastName.'.';
                return;
            }

            if (
                $this->candidates->count() &&
                ($this->candidates->count() === 1) &&
                ($this->candidates->first()->version_id == $this->versionId)
            ) {

                $this->updateCandidateVars($this->candidates->first());
            } else {
                //do nothing?
            }

        } else {
            $this->candidateError = "Candidate name must be at least ".$minLength." characters.";
        }
    }

    public function save()
    {
        $this->reset('scoreUpdatedMssg');

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
            $judge = Judge::find($this->judgeId);
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
                    'judge_id' => $this->judgeId,
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

        $this->form->setScoreTolerance();

        event(new UpdateAuditionResultsEvent($this->form->candidate));

        $this->scoreUpdatedMssg = 'Last update: '.Carbon::now('America/New_York')->format('D, M d @ g:i:s a');
    }

    public function updatedCandidateId(): void
    {
        $this->reset('candidateError', 'candidates',
            'candidateName', 'candidateSchool', 'candidateTeacher', 'candidateVoicePartDescr',
            'lastName', 'rooms', 'roomId',
        );

        //edit input value in case user uses the ref#
        $candidateId = str_replace('-', '', $this->candidateId);

        //clear page if no $candidateId
        if (empty($candidateId)) {
            $this->reset('candidateError');
            return;
        }

        //validation
        if (!str_starts_with($candidateId, $this->versionId)) {
            $this->candidateError = 'Invalid candidate id';
            return;
        }

        $minLength = (strlen($this->versionId) + 4);
        if (strlen($candidateId) !== $minLength) {
            $this->candidateError = "Candidate id ($this->candidateId) must have ".$minLength." numeric characters.";
            $this->reset('candidateId');
            return;
        }

        $candidate = Candidate::query()
            ->where('id', $candidateId)
            ->with('school', 'student', 'student.user', 'voicePart', 'teacher', 'teacher.user')
            ->first();

        if (!$candidate) {
            $this->candidateError = 'Invalid candidate id.';
            return;
        }

        $voicePartId = $candidate->voice_part_id;

        $this->rooms = Room::query()
            ->join('room_voice_parts', 'room_voice_parts.room_id', '=', 'rooms.id')
            ->where('rooms.version_id', $this->versionId)
            ->where('room_voice_parts.voice_part_id', $voicePartId)
            ->select('rooms.*')
            ->get();

        if (!$this->rooms->count()) {
            $this->candidateError = "No rooms found.";
            return;
        }

        if (!$this->rooms->first()->judges->count()) {
            $this->candidateError = "No judges found for ".$this->rooms->first()->room_name.".";
            return;
        }

        if (!$candidate) {
            $this->candidateError = "$this->candidateId is not a valid id.";
            $this->reset('candidateId');
            return;
        }

        $this->updateRooms($candidate);
        $this->updateCandidateVars($candidate);

        $this->selectedVoicePartId = $candidate->voice_part_id;
        $this->candidateScoreCount = Score::where('candidate_id', $candidateId)->count('id');
    }

    public function updatedJudgeId()
    {
        $this->reset('scoreUpdatedMssg');

        $this->judge = Judge::find($this->judgeId);

        $scores = Score::query()
            ->where('candidate_id', $this->candidateId)
            ->where('judge_id', $this->judgeId)
            ->pluck('score', 'score_factor_id')
            ->toArray() ?? [];

        foreach ($this->form->factors as $factor) {
            $this->form->scores[$factor->id] = $scores[$factor->id] ?? $factor->best;
        }
    }

    public function updatedRoomId(): void
    {
        $this->reset('scoreUpdatedMssg', 'judgeId');

        $this->room = $this->rooms->where('id', $this->roomId)->first();

        if (!$this->room) {
            $this->candidateError = "No room found.";
            return;
        }

        if (!$this->room->judges->count()) {
            $this->candidateError = "No judges found for ".$this->rooms->first()->room_name.".";
            return;
        }

        $this->updateRooms(Candidate::find($this->candidateId));
    }

    private function getEventVoiceParts(): Collection
    {
        $event = Version::find($this->versionId)->event;

        return $event->getVoicePartsAttribute();
    }

    private function updateCandidateVars(Candidate $candidate): void
    {
        $this->candidateRef = $candidate->ref;
        $this->candidateName = $candidate['student']['user']->name;
        $this->candidateSchool = $candidate['school']->name;
        $this->candidateTeacher = $candidate['teacher']['user']->name;
        $this->candidateVoicePartDescr = $candidate['voicePart']->descr;
    }

    private function updateRooms(Candidate $candidate): void
    {

        //set defaults
        $candidate = Candidate::find($this->candidateId);
        $this->roomId = ($this->roomId) ?: $this->rooms->first()->id;
        $this->room = $this->rooms->where('id', $this->roomId)->first();
        $this->judges = $this->room->judges;

        $this->judgeId = $this->judgeId ?: $this->judges->first()->id;
        $this->judge = Judge::find($this->judgeId);

        $this->form->setCandidate($candidate, $this->room, $this->judges->first());
        $this->form->setRoom($this->room, $this->judge);
    }
}
