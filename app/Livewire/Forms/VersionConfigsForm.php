<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class VersionConfigsForm extends Form
{
    public bool $alternatingScores = true;
    public bool $averagedScores = false;
    public string $fileTypes;
    public int $fileUploadCount = 1;
    public int $judgeCount = 1;
    public bool $roomMonitor = false;
    public bool $scoreAscending = true;
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
