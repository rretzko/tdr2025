<?php

namespace App\Livewire\Libraries\Items;

use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\LibStack;
use App\Services\Libraries\CreateLibItemService;
use App\Services\Libraries\LibraryStackSearchService;
use JetBrains\PhpStorm\NoReturn;

class ItemComponent extends BaseLibraryItemPage
{
    public string $errorMessage = '';
    public array $itemTypes = [];
    public int $libraryId = 0;
    public string $searchResults = 'Search Results';

    public function mount(): void
    {
        parent::mount();

        $this->libraryId = $this->dto['id'];

        $this->itemTypes = self::ITEMTYPES;

    }

    public function render()
    {
        return view('livewire..libraries.items.item-component',
            [
                'bladeForm' => 'components.forms.libraries.itemTypes.' . $this->form->itemTypeBlade() . 'Form',
            ]);
    }

    #[NoReturn] public function findItem(int $itemId): void
    {
        dd($itemId);
    }

    public function save(): void
    {
        $this->reset('errorMessage', 'successMessage');

        dd(__METHOD__);
    }

    public function saveAndStay(): void
    {
        $this->reset('errorMessage', 'successMessage');

        $service = new CreateLibItemService($this->form, self::ITEMTYPES);

        if ($service->saved) {
            $this->addItemToLibrary($service->libItemId);
            $this->successMessage = 'Item Saved.';
        } else {
            $this->errorMessage = 'Unable to save item.';
        }

    }

    public function updatedFormTitle()
    {
        $this->search();
    }

    private function addItemToLibrary(int $libItemId): void
    {
        LibStack::updateOrCreate(
            [
                'library_id' => $this->libraryId,
                'lib_item_id' => $libItemId
            ],
            []
        );
    }

    private function search(): void
    {
        $search = new LibraryStackSearchService($this->form);

        $this->searchResults = $search->getResults();
    }

}
