<?php

namespace App\Livewire\Ensembles\Inventories;

use App\Models\Ensembles\Asset;
use Illuminate\Support\Str;

class InventoryMassAddComponent extends InventoryCreateComponent
{
    public array $oneToThreeHundred = [];

    public function mount(): void
    {
        parent::mount();

        $oneToThreeHundred = range(1, 300);
        //set index to start at 1
        $this->oneToThreeHundred = array_combine(range(1, count($oneToThreeHundred)), $oneToThreeHundred);
    }

    public function render()
    {
        return view('livewire..ensembles.inventories.inventory-mass-add-component',
            [
                'statuses' => parent::INVENTORYSTATUSES,
            ]);
    }

    public function save(): void
    {
        $this->form->addMultiple();

        $this->redirectRoute('ensembles.inventory');
    }

    public function saveAndStay(): void
    {
        dd(__METHOD__);
    }

    public function updatedFormAssetId(): void
    {
        $this->form->assetName = Asset::find($this->form->assetId)->name;
        $this->form->assetNamePlural = Str::plural($this->form->assetName);
    }

//    public function updatedFormAssetIdStartingPoint(): void
//    {
//        dd($this->form->assetIdStartingPoint);
//        if(
//            is_null($this->form->assetIdStartingPoint) ||
//            is_nan($this->form->assetIdStartingPoint)
//        ) {
//            $this->form->assetIdStartingPoint = 1;
//        }
//    }
}
