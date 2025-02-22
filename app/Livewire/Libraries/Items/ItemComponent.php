<?php

namespace App\Livewire\Libraries\Items;

use App\Models\Libraries\Items\Components\LibTitle;

class ItemComponent extends BaseLibraryItemPage
{
    public string $errorMessage = '';
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
        $this->reset('errorMessage', 'successMessage');

        dd(__METHOD__);
    }

    public function saveAndStay(): void
    {
        $this->reset('errorMessage', 'successMessage');

        if ($this->form->save()) {
            $this->successMessage = 'Item Saved.';
        } else {
            $this->errorMessage = 'Unable to save item.';
        }

    }

    public function updatedFormTitle()
    {
        $this->search('title', $this->form->title);
    }

    private function search(string $property, string $value): void
    {
        $searchString = '%' . $value . '%';

        $this->searchResults = '<div><h3>SearchResults</h3>';

        $titles = LibTitle::where('title', 'LIKE', $searchString)->pluck('title', 'id')->toArray();

        if (count($titles)) {

            $this->searchResults .= '<ul>';

            foreach ($titles as $id => $title) {

                $this->searchResults .= "<li><a href='findItem: $id'>$title</a></li>";
            }

            $this->searchResults .= '</ul>';
        }

        $this->searchResults .= '</div>';
    }

}
