<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class VersionConfigsRegistrantsForm extends Form
{
    public string $sysId = 'new';

    public function update()
    {
        if ($this->sysId === 'new') {

            $this->add();

        } else {

            //update the model
        }
    }

    private function add()
    {

    }
}
