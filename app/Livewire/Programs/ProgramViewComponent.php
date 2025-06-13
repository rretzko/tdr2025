<?php

namespace App\Livewire\Programs;

use App\Livewire\BasePage;
use App\Livewire\Forms\ProgramSelectionForm;
use App\Models\Ensembles\Ensemble;
use App\Models\Libraries\Items\Components\Artist;
use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Programs\Program;
use App\Models\Schools\GradesITeach;
use App\Models\Schools\Teacher;


class ProgramViewComponent extends BasePage
{
    public Program $program;
    public ProgramSelectionForm $form;
    public array $artistTypes = [];
    public string $ensembleName = '';
    public string $ensembleNameError = '';
    public array $ensembles = [];
    public array $resultsArranger = [];
    public array $resultsChoreographer = [];
    public array $resultsComposer = [];
    public array $resultsMusic = [];
    public array $resultsWam = [];
    public array $resultsWords = [];
    public array $resultsSelectionTitle = [];
    public int $schoolId = 0;
    public string $selectionTitle = '';

    public function mount(): void
    {
        parent::mount();

        $this->artistTypes = [
            'composer',
            'arranger',
            'wam',
            'music',
            'words',
            'choreographer'
        ];
        $this->program = Program::find($this->dto['programId']);
        $this->ensembles = $this->getEnsembles();
        $this->schoolId = $this->program->school_id;
        $this->form->schoolId = $this->schoolId;

        if (count($this->ensembles)) {
            $this->form->ensembleId = array_key_first($this->ensembles);
        }

    }

    private function getEnsembles(): array
    {
        return Ensemble::query()
            ->where('school_id', $this->program->school_id)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function render()
    {
        return view('livewire..programs.program-view-component');
    }

    public function clickArtist(string $type, int $artistId)
    {
        $artist = Artist::find($artistId);
    }

    public function clickTitle(int $libTitleId): void
    {
        $libTitle = LibTitle::find($libTitleId);
        $this->selectionTitle = $libTitle->title;
        $this->reset('selectionTitleResults');
        $this->form->libTitleId = $libTitleId;
    }

    /**
     * Method activates when user enters a new ensemble name into ProgramSelectionForm::ensembleName field
     * if the ensemble name exists, return failure message to user, else
     * - make a new Ensemble with default values
     * - populate $form->ensembleId with new ensemble id
     * @return void
     */
    public function updatedEnsembleName(): void
    {
        $this->reset('ensembleNameError');

        if ($this->ensembleNameUnique()) {

            $teacherId = Teacher::where('user_id', auth()->id())->first()->id;

            $grades = GradesITeach::query()
                ->where('school_id', $this->schoolId)
                ->where('teacher_id', $teacherId)
                ->pluck('grade')
                ->toArray();

            $abbr = Ensemble::makeEnsembleNameAbbreviation($this->ensembleName);

            $ensemble = Ensemble::create(
                [
                    'name' => $this->ensembleName,
                    'short_name' => substr($this->ensembleName, 0, 16),
                    'school_id' => $this->schoolId,
                    'teacher_id' => $teacherId,
                    'abbr' => $abbr,
                    'description' => $this->ensembleName.' description',
                    'active' => 1,
                    'grades' => implode(',', $grades)
                ]
            );

            $this->form->ensembleId = $ensemble->id;
            $this->ensembles = $this->getEnsembles();
        } else {
            $this->ensembleNameError = 'The ensemble name <b>'.$this->ensembleName.'</b> already exists and has been selected above.';
            $this->form->ensembleId = $this->getDuplicateEnsembleNameId();
            $this->reset('ensembleName');
        }
    }

    private function ensembleNameUnique(): bool
    {
        return !Ensemble::query()
            ->where('school_id', $this->schoolId)
            ->where('name', $this->ensembleName)
            ->exists();
    }

    private function getDuplicateEnsembleNameId(): int
    {
        return Ensemble::query()
            ->where('school_id', $this->schoolId)
            ->where('name', $this->ensembleName)
            ->value('id');
    }

    public function updatedSelectionTitle(): void
    {
        $this->resultsSelectionTitle = LibTitle::query()
            ->where('title', 'LIKE', '%'.$this->selectionTitle.'%')
            ->pluck('title', 'id')
            ->toArray();
    }
}
