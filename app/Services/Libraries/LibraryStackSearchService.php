<?php

namespace App\Services\Libraries;

use App\Livewire\Forms\LibraryItemForm;
use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\Items\LibItem;

class LibraryStackSearchService
{
    private array $itemIds = [];
    private string $results = 'none found';

    public function __construct(private readonly LibraryItemForm $form)
    {
        $this->init();
    }

    private function init(): void
    {
        $this->getItemIdsFromTitle();

        $this->results = $this->setSearchResults();
//        dd($itemIds);

    }

    private function getItemIdsFromTitle(): void
    {
        //early exit
        if (!strlen($this->form->title)) {
            return;
        }

        $searchString = $this->buildSearchString($this->form->title);

        $titleIds = LibTitle::where('title', 'LIKE', $searchString)->pluck('id')->toArray();

        $this->itemIds = LibItem::whereIn('lib_title_id', $titleIds)->pluck('id')->toArray();
    }

    private function buildSearchString(string $string): string
    {
        return '%' . $string . '%';
    }

    private function setSearchResults(): string
    {
        $str = '<div><h3 class="underline">Search Results</h3>';

        if (count($this->itemIds)) {

            $str .= '<ul class="text-sm">';

            foreach ($this->itemIds as $itemId) {

                $libItem = LibItem::find($itemId);
                $title = LibTitle::find($libItem->lib_title_id)->title;

                $str .= "
                    <li class='text-left'>
                        <button wire:click='findItem($libItem->id)' class='px-1 cursor-pointer hover:underline hover:bg-gray-200 '>
                            $title
                        </button>
                    </li>";
            }

            $str .= '</ul>';
        } else {
            $str .= '<ul class="text-sm"><li>None found.</li></ul>';
        }

        $str .= '</div>';

        return $str;
    }

    public function getResults(): string
    {
        return $this->results;
    }
}
