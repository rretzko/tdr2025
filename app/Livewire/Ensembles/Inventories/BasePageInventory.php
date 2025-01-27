<?php

namespace App\Livewire\Ensembles\Inventories;

use App\Livewire\BasePage;
use App\Livewire\Forms\InventoryForm;
use App\Models\Ensembles\Asset;
use App\Models\Ensembles\AssetEnsemble;


class BasePageInventory extends BasePage
{
    public const INVENTORYSTATUSES =
        [
            'assigned' => 'assigned',
            'available' => 'available',
            'lost' => 'lost',
            'removed' => 'removed',
            'unreturned' => 'unreturned',
        ];
    public array $assets;
    public InventoryForm $form;

    public function mount(): void
    {
        parent::mount();

        $this->form->userId = auth()->id();

        $this->form->creator = auth()->user()->name;
    }

    public function getAssests(): array
    {
        if (!$this->form->ensembleId) {
            return [];
        }

        return AssetEnsemble::query()
            ->join('assets', 'asset_ensemble.asset_id', '=', 'assets.id')
            ->where('asset_ensemble.ensemble_id', $this->form->ensembleId)
            ->orderBy('assets.name')
            ->pluck('assets.name', 'assets.id')
            ->toArray();
    }
}
