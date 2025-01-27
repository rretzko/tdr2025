<?php

namespace App\Livewire\Ensembles\Inventories;

use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Inventories\Inventory;
use App\Models\UserConfig;
use Illuminate\Routing\Redirector;

class InventoryCreateComponent extends BasePageInventory
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
    }
    public function render()
    {
        return view('livewire..ensembles.inventories.inventory-create-component',
            [
                'statuses' => self::INVENTORYSTATUSES,
            ]);
    }

    public function updatedFormItemId()
    {
        $itemIdExists = Inventory::query()
            ->where('asset_id', $this->form->assetId)
            ->where('item_id', $this->form->itemId)
            ->exists();

        if ($itemIdExists) {
            dd('Item_id ' . $this->form->itemId . ' exists and cannot be re-used.');
        }
    }

    public function save()
    {
        $this->form->update();

        $this->successMessage = 'Inventory item successfully added.';

        return $this->redirectRoute('ensembles.inventory');
    }

    public function saveAndStay()
    {
        $this->form->update();

        $this->successMessage = 'Inventory item successfully added.';

        return $this->redirectRoute('inventory.create');
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
