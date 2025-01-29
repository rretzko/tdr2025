<?php

namespace App\Livewire\Ensembles\Inventories;

use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Inventories\Inventory;
use App\Models\UserConfig;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

class InventoryCreateComponent extends BasePageInventory
{
    public array $ensembles = [];
    public string $duplicateItemIdMessage = '';

    public function mount(): void
    {
        parent::mount();

        $this->ensembles = $this->getEnsembles();

        $this->form->ensembleId = array_key_first($this->ensembles);

        //$this->form->ensembleId must be a valid id number to return assets
        $this->assets = $this->getAssests();
        if (!$this->form->assetName) {
            $this->form->assetName = $this->assets[array_key_first($this->assets)];
            $this->form->assetNamePlural = Str::plural($this->form->assetName);
        }

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
            ->where('ensemble_id', $this->form->ensembleId)
            ->where('asset_id', $this->form->assetId)
            ->where('item_id', $this->form->itemId)
            ->exists();

        if ($itemIdExists) {
            $this->duplicateItemIdMessage = ('Item id #' . $this->form->itemId . ' exists and cannot be re-used. This value will be incremented if saved.');
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
