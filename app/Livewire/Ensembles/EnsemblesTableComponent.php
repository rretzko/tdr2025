<?php

namespace App\Livewire\Ensembles;

use App\Livewire\Ensembles\BasePageEnsemble;
use App\Livewire\Forms\EnsembleForm;
use App\Models\Ensembles\Ensemble;

class EnsemblesTableComponent extends BasePageEnsemble
{
    public EnsembleForm $form;

    public function mount(): void
    {
        parent::mount();

        if (count($this->schools) > 1) {

            $this->hasFilters = true;
        }
    }

    public function render()
    {
        return view('livewire..ensembles.ensembles-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getEnsembles(),
            ]);
    }

    private function getColumnHeaders(): array
    {
        return [
            'name/school', 'short name', 'abbr', 'description', 'active',
        ];
    }

    private function getEnsembles(): array
    {
        return Ensemble::query()
            ->where('school_id', $this->school->id)
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    private function getSchools(): array
    {
        return auth()->user()->teacher->schools
            ->pluck('name', 'id')
            ->toArray();
    }
}
