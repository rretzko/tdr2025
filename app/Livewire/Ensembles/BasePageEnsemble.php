<?php

namespace App\Livewire\Ensembles;

use App\Livewire\BasePage;
use App\Livewire\Forms\EnsembleForm;
use App\Models\Schools\School;


class BasePageEnsemble extends BasePage
{
    public EnsembleForm $form;

    public function mount(): void
    {
        parent::mount();

        if ($this->school->id) {
            $this->form->setSchool($this->school);
        }
    }
}
