<?php

namespace App\Livewire\Forms;

use App\Models\Programs\ProgramSelection;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProgramSelectionForm extends Form
{
    public string $addendum1 = '';
    public string $addendum2 = '';
    public string $addendum3 = '';
    public int $arrangerId = 0;
    public string $artistBlock = '';
    public string $bgColor = 'bg-gray-100';
    public int $choreographerId = 0;
    public int $composerId = 0;
    public int $ensembleId = 0;
    public string $headerText = 'Add New Concert Selection';
    public string $itemType = 'sheet music';
    public int $libTitleId = 0;
    public int $performanceOrderBy = 1;
    public ProgramSelection $programSelection;
    public int $programSelectionId = 0;
    public int $schoolId = 0;
    public string $voicing = '';
    public int $wamId = 0;
    public int $wordsId = 0;

    public function resetVars(): void
    {
        $this->programSelection = new ProgramSelection();

        $this->artistBlock = '';
        $this->bgColor = 'bg-gray-100';
        $this->ensembleId = 0;
        $this->headerText = 'Add Concert Selection';
        $this->performanceOrderBy = 0;
        $this->programSelectionId = 0;
        $this->voicing = '';
    }

    public function setVars(int $programSelectionId): void
    {
        $this->programSelection = ProgramSelection::find($programSelectionId);

        $this->artistBlock = $this->programSelection->artistBlock;
        $this->bgColor = 'bg-green-100';
        $this->ensembleId = $this->programSelection->ensemble_id;
        $this->headerText = 'Edit "<b>'.$this->programSelection->title.'"</b> Concert Selection';
        $this->performanceOrderBy = $this->programSelection->order_by;
        $this->programSelectionId = $programSelectionId;
        $this->voicing = $this->programSelection->voicing;

    }

    public function update(): bool
    {
        return $this->programSelection->update(
            [
                'ensemble_id' => $this->ensembleId,
                'order_by' => $this->performanceOrderBy,
            ]
        );
    }
}
