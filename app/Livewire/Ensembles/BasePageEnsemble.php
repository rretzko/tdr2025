<?php

namespace App\Livewire\Ensembles;

use App\Livewire\BasePage;
use App\Livewire\Forms\EnsembleForm;
use App\Models\Ensembles\Asset;
use App\Models\Ensembles\Ensemble;
use App\Models\Schools\School;
use Illuminate\Support\Collection;


class BasePageEnsemble extends BasePage
{
    public Collection $assets;
    public array $ensembleAssets = [];
    public Ensemble $ensemble;
    public EnsembleForm $form;

    public function mount(): void
    {
        parent::mount();

        if ($this->school->id) {
            $this->form->setSchool($this->school);
        }

        $this->ensemble = ($this->dto['id'])
            ? Ensemble::find($this->dto['id'])
            : new Ensemble;

        if ($this->ensemble->id) {

            $this->form->setEnsemble($this->ensemble);
        }

        $this->assets = Asset::orderBy('name')->get();
    }
}
