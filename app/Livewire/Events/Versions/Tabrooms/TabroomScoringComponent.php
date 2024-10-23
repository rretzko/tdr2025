<?php

namespace App\Livewire\Events\Versions\Tabrooms;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\UserConfig;
use Illuminate\Validation\Rules\Can;
use Illuminate\Support\Collection;

class TabroomScoringComponent extends BasePage
{
    public string $candidateError = '';
    public string $candidateId = '';
    public string $candidateName = '';
    public Collection $candidates;
    public string $candidateSchool = '';
    public string $candidateTeacher = '';
    public string $candidateVoicePartDescr = '';
    public string $lastName = '';
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

    public function updatedLastName(): void
    {
        $this->reset('candidateError', 'candidates',
            'candidateName', 'candidateSchool', 'candidateTeacher', 'candidateVoicePartDescr');

        $minLength = 2;

        if (strlen($this->lastName) >= $minLength) {

            $this->candidates = Candidate::query()
                ->where('program_name', 'LIKE', '%'.$this->lastName.'%')
                ->with('school', 'student', 'student.user', 'voicePart', 'teacher', 'teacher.user')
                ->get();
//
//            $this->candidateName = $candidate['student']['user']->name;
//            $this->candidateSchool = $candidate['school']->name;
//            $this->candidateTeacher = $candidate['teacher']['user']->name;
//            $this->candidateVoicePartDescr = $candidate['voicePart']->descr;
        } else {
            $this->candidateError = "Candidate name must be at least ".$minLength." characters.";
        }
    }

    public function updatedCandidateId(): void
    {
        $this->reset('candidateError', 'candidates',
            'candidateName', 'candidateSchool', 'candidateTeacher', 'candidateVoicePartDescr');

        $minLength = (strlen($this->versionId) + 4);

        //edit input value in case user uses the ref#
        $candidateId = str_replace('-', '', $this->candidateId);

        //validation
        if (substr($candidateId, 0, strlen($this->versionId)) != $this->versionId) {
            $this->candidateError = 'Invalid candidate id';
            return;
        }

        if (strlen($candidateId) === $minLength) {

            $candidate = Candidate::query()
                ->where('id', $candidateId)
                ->with('school', 'student', 'student.user', 'voicePart', 'teacher', 'teacher.user')
                ->first();

            $this->candidateName = $candidate['student']['user']->name;
            $this->candidateSchool = $candidate['school']->name;
            $this->candidateTeacher = $candidate['teacher']['user']->name;
            $this->candidateVoicePartDescr = $candidate['voicePart']->descr;
        } else {
            $this->candidateError = "Candidate id must have ".$minLength." numeric characters.";
        }
    }
}
