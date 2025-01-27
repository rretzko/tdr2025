<?php

namespace App\Livewire\Ensembles\Inventories;

use App\Models\Ensembles\Ensemble;
use App\Models\UserConfig;

class InventoryEditComponent extends BasePageInventory
{
    public array $ensembles = [];

    public function mount(): void
    {
        parent::mount();

        $this->ensembles = $this->getEnsembles();

        $this->form->ensembleId = array_key_first($this->ensembles);

        //$this->form->ensembleId must be a valid id number to return assets
        $this->assets = $this->getAssests();

        $this->form->assetId = array_key_first($this->assets);

        $this->form->setInventoryItem($this->dto['inventoryId']);
    }
    public function render()
    {
        return view('livewire..ensembles.inventories.inventory-edit-component',
            [
                'statuses' => self::INVENTORYSTATUSES,
            ]);
    }

    public function save()
    {
        $this->form->update();

        $this->successMessage = 'Inventory item successfully updated.';

        return $this->redirectRoute('ensembles.inventory');
    }

    private function getEnsembles(): array
    {
        $schoolId = UserConfig::getValue('schoolId');

        return Ensemble::query()
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
