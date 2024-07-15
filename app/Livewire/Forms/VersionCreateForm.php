<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class VersionCreateForm extends Form
{
    public string $sysId = 'new';
    public string $name = 'Test';
    public string $shortName = '';
}
