<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class EnsembleLibraryForm extends Form
{
    public int $libItemsId = 0;
    public string $title = '';
}
