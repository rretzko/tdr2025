<?php

namespace App\Livewire\Ensembles\Inventories;

use Illuminate\Routing\Redirector;

class InventoryCreateComponent extends BasePageInventory
{
    public function render()
    {
        return view('livewire..ensembles.inventories.inventory-create-component',
            [
                'statuses' => self::INVENTORYSTATUSES,
            ]);
    }

    public function save()
    {
        $this->form->update();

        $this->successMessage = 'Inventory item successfully added.';

        return $this->redirectRoute('inventories');
    }
}
