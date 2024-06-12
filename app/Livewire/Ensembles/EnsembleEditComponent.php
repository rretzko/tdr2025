<?php

namespace App\Livewire\Ensembles;


class EnsembleEditComponent extends BasePageEnsemble
{
    public function mount(): void
    {
        parent::mount();

    }

    public function render()
    {
        return view('livewire..ensembles.ensemble-edit-component');
    }

    public function save()
    {
        $this->form->update();

        return redirect()->route('ensembles');
    }
}
