<?php

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Livewire\BasePage;
use App\Livewire\Forms\AdjudicationForm;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Room;
use App\Models\UserConfig;
use Illuminate\Validation\Rules\Can;
use Illuminate\Support\Collection;

class TabroomScoringComponent extends BasePage
{
    public AdjudicationForm $form;
    public string $candidateError = '';
    public string $candidateId = '';
    public string $candidateName = '';
    public string $candidateRef = '';
    public Collection $candidates;
    public string $candidateSchool = '';
    public string $candidateTeacher = '';
    public string $candidateVoicePartDescr = '';
    public string $lastName = '';
    public int $roomId = 0;
    public Collection $rooms;
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->candidates = collect();
        $this->versionId = UserConfig::getValue('versionId');
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


            }

        } else {
            $this->candidateError = "Candidate name must be at least ".$minLength." characters.";
        }
    }

    public function updatedCandidateId(): void
    {
        $this->reset('candidateError', 'candidates',
            'candidateName', 'candidateSchool', 'candidateTeacher', 'candidateVoicePartDescr',
            'lastName', 'rooms',
        );

        $minLength = (strlen($this->versionId) + 4);

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

        if (strlen($candidateId) === $minLength) {

            $candidate = Candidate::query()
                ->where('id', $candidateId)
                ->with('school', 'student', 'student.user', 'voicePart', 'teacher', 'teacher.user')
                ->first();

            $this->updateCandidateVars($candidate);

            $this->updateRooms($candidate);

        } else {
            $this->candidateError = "Candidate id must have ".$minLength." numeric characters.";
        }
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
        $voicePartId = $candidate->voice_part_id;

        $this->rooms = Room::query()
            ->join('room_voice_parts', 'room_voice_parts.room_id', '=', 'rooms.id')
            ->where('rooms.version_id', $this->versionId)
            ->where('room_voice_parts.voice_part_id', $voicePartId)
            ->get();

        //set default
        $this->roomId = $this->rooms->first()->id;

        //set Room defaults
        $candidate = Candidate::find($this->candidateId);
        $room = Room::find($this->roomId);
        $judges = $room->judges();
//        $this->form->displayOnly = true;
        $this->form->setCandidate($candidate, $room, $judges->first());
    }
}
