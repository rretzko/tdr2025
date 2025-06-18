<?php

namespace App\Livewire\Forms;

use App\Models\Programs\ProgramAddendum;
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

        $this->addendum1 = '';
        $this->addendum2 = '';
        $this->addendum3 = '';
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

        $this->setAddendumVars($programSelectionId);

    }

    public function update(): bool
    {
        $this->updateProgramAddendums();

        return $this->programSelection->update(
            [
                'ensemble_id' => $this->ensembleId,
                'order_by' => $this->performanceOrderBy,
            ]
        );
    }

    private function setAddendumVars(int $programSelectionId): void
    {
        $addendums = ProgramAddendum::where('program_selection_id', $programSelectionId)->get();
        foreach ($addendums as $key => $addendum) {
            $index = $key + 1;
            $var = 'addendum'.$index;
            $this->$var = $addendum->addendum;
        }
    }

    private function updateProgramAddendums(): void
    {
        //remove all current addendums
        ProgramAddendum::where('program_selection_id', $this->programSelectionId)->delete();

        // Collect non-empty addendums
        $addendums = array_filter([
            $this->addendum1,
            $this->addendum2,
            $this->addendum3,
        ], fn($addendum) => strlen($addendum) > 0);

        if (empty($addendums)) {
            return;
        }

        // Prepare data for batch insert
        $insertData = array_map(fn($addendum) => [
            'program_selection_id' => $this->programSelectionId,
            'addendum' => $addendum,
        ], $addendums);

        // Insert all addendums in one query
        ProgramAddendum::insert($insertData);
    }
}
