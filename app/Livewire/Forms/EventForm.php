<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class EventForm extends Form
{
    public int $ensembleCountId = 0;
    public string $grades = '';
    public
    string $logo = '';
    public int $maxRegistrants = 30;
    public string $name = '';
    public string $orgName = '';
    public bool $requiredHeight = false;
    public bool $requiredShirtSize = false;
    public string $shortName = '';
    public int $statusId = 3; //default=sandbox
    public string $sysId = 'new';

    //ensemble values
    public array $ensembles = [];

}
