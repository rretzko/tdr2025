<?php

namespace App\Livewire\Ensembles;

use App\Livewire\Ensembles\BasePageEnsemble;

class EnsembleCreateComponent extends BasePageEnsemble
{
    public function render()
    {
        return view('livewire..ensembles.ensemble-create-component',
            [
                'schools' => $this->schools,
            ]);
    }

    public function save()
    {
        $this->form->update();

        return redirect('ensembles');
    }
}
