<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ProgramSelectionForm extends Form
{
    public int $arrangerId = 0;
    public int $choreographerId = 0;
    public int $composerId = 0;
    public int $ensembleId = 0;
    public string $itemType = 'sheet music';
    public int $libTitleId = 0;
    public int $performanceOrderBy = 1;
    public int $schoolId = 0;
    public int $wamId = 0;
    public int $wordsId = 0;
}
