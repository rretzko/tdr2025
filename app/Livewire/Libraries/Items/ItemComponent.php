<?php

namespace App\Livewire\Libraries\Items;

use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\LibStack;
use App\Models\Libraries\Items\LibItem;
use App\Services\Libraries\CreateLibItemService;
use App\Services\Libraries\LibraryStackSearchService;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;

class ItemComponent extends BaseLibraryItemPage
{
    public string $errorMessage = '';
    public array $itemTypes = [];
    public int $libraryId = 0;
    public LibItem $libItem;
    public string $searchResults = 'Search Results';

    public function mount(): void
    {
        parent::mount();

        $this->libraryId = $this->dto['id'];

        $this->itemTypes = self::ITEMTYPES;

        if (isset($this->dto['libItem'])) {
            $this->libItem = $this->dto['libItem'];
            $this->form->setLibItem($this->libItem);
        } else {
            $this->form->resetVars();
        }

    }

    public function render()
    {
        return view('livewire..libraries.items.item-component',
            [
                'bladeForm' => 'components.forms.libraries.itemTypes.' . $this->form->itemTypeBlade() . 'Form',
            ]);
    }

    #[NoReturn] public function findItem(int $libItemId): void
    {
        $this->form->setLibItem(LibItem::find($libItemId));
    }

    public function save()
    {
        //determine if save = updating or adding
        $updating = (bool)$this->form->sysId;

        $this->reset('errorMessage', 'successMessage');

        $saved = $this->form->save($this->libraryId);
        $fTitle = Str::title($this->form->title);

        if ($saved) {
            $message = '"' . $fTitle . ($updating ? '" updated.' : '" saved.');
            session()->flash('successMessage', $message);
        } else {
            $this->errorMessage = 'Unable to save "' . $fTitle . '" at this time.';
        }

        return $this->redirect("/library/$this->libraryId/items");
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
