<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class LibraryItemForm extends Form
{
    public string $itemType = 'sheetMusic';
    public int $sysId = 0;
    public string $title = 'Item title';


    public function save(): bool
    {
        return false;
    }
}
