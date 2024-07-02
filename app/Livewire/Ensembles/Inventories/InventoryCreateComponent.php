<?php

namespace App\Livewire\Ensembles\Inventories;

class InventoryCreateComponent extends BasePageInventory
{
    public function render()
    {
        return view('livewire..ensembles.inventories.inventory-create-component',
            [
                'statuses' => self::INVENTORYSTATUSES,
            ]);
    }
}
