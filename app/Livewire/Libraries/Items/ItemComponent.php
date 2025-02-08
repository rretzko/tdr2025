<?php

namespace App\Livewire\Libraries\Items;

class ItemComponent extends BaseLibraryItemPage
{
    public array $itemTypes = [];
    public string $searchResults = 'Search Results';

    public function mount(): void
    {
        parent::mount();

        $this->itemTypes = self::ITEMTYPES;

    }

    public function render()
    {
        return view('livewire..libraries.items.item-component',
            [
                'bladeForm' => 'components.forms.libraries.itemTypes.' . $this->form->itemType . 'Form',
            ]);
    }

    public function save(): void
    {
        dd(__METHOD__);
    }

    public function saveAndStay(): void
    {
        dd(__METHOD__);
    }


}
