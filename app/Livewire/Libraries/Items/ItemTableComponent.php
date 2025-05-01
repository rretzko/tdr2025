<?php

namespace App\Livewire\Libraries\Items;

use App\Livewire\BasePage;
use App\Models\Libraries\Items\LibItem;
use App\Models\Libraries\Library;
use App\Models\Libraries\LibStack;
use JetBrains\PhpStorm\NoReturn;

class ItemTableComponent extends BasePage
{
    public array $columnHeaders;
    public Library $library;
    public bool $displayForm = false;
    public string $globalSearch = '';
    public string $likeValue = ''; //wrap $globalSearch in %%
    public bool $hasSearch = true;

    public function mount(): void
    {
        parent::mount();

        $this->library = Library::find($this->dto['id']);

        $this->columnHeaders = $this->getColumnHeaders();

        $this->sortCol = 'lib_titles.alpha';

        $this->updatedGlobalSearch(); //set initial $this->likeValue to "%%"
    }

    public function render()
    {
        return view('livewire..libraries.items.item-table-component',
            [
                'rows' => $this->getRows(),
            ]
        );
    }

    /**
     * @return null
     */
    public function clickForm()
    {
        return $this->redirect("/library/{$this->dto['id']}/item/new");
    }

    public function edit(int $libItemId)
    {
        //ex. "library\1\edit\1"
        $url = DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.$this->library->id.DIRECTORY_SEPARATOR.'edit'.DIRECTORY_SEPARATOR.$libItemId;

        return $this->redirect($url);
    }

    public function remove(int $libItemId): void
    {
        $libItemTitle = LibItem::find($libItemId)->title;
        $libStack = LibStack::query()
            ->where('library_id', $this->library->id)
            ->where('lib_item_id', $libItemId)
            ->first();

        if ($libStack->delete()) {
            $message = '"' . $libItemTitle . '" has been removed from this library.';
            session()->flash('successMessage', $message);
        }

    }

    public function sortBy(string $key): void
    {
        $this->sortColLabel = $key;

        $properties = [
            'title' => 'lib_titles.alpha',
            'type' => 'lib_items.item_type',
            'voicing' => 'voicings.descr',
            'artists' => 'artists.last_name',
        ];

        $requestedSort = $properties[$key];

        //toggle $this->sortAsc if user clicks on the same column header twice
        if ($requestedSort === $this->sortCol) {

            $this->sortAsc = (!$this->sortAsc);
        }

        $this->sortCol = $properties[$key];

    }

    public function updatedGlobalSearch(): void
    {
        $this->likeValue = '%'.$this->globalSearch.'%';
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'type', 'sortBy' => 'type'],
            ['label' => 'title', 'sortBy' => 'title'],
            ['label' => 'artists', 'sortBy' => null],
            ['label' => 'voicing', 'sortBy' => 'voicing'],
        ];
    }

    private function getRows(): array
    {
        return LibStack::query()
            ->join('lib_items', 'lib_stacks.lib_item_id', '=', 'lib_items.id')
            ->join('lib_titles', 'lib_items.lib_title_id', '=', 'lib_titles.id')
            ->leftJoin('artists AS composer', 'lib_items.composer_id', '=', 'composer.id')
            ->leftJoin('artists AS arranger', 'lib_items.arranger_id', '=', 'arranger.id')
            ->leftJoin('artists AS wam', 'lib_items.wam_id', '=', 'wam.id')
            ->leftJoin('artists AS words', 'lib_items.words_id', '=', 'words.id')
            ->leftJoin('artists AS music', 'lib_items.music_id', '=', 'music.id')
            ->leftJoin('artists AS choreographer', 'lib_items.choreographer_id', '=', 'choreographer.id')
            ->leftJoin('voicings', 'lib_items.voicing_id', '=', 'voicings.id')
            ->where('lib_stacks.library_id', $this->library->id)
            ->where(function ($query) {
                $query->where('lib_titles.title', 'LIKE', $this->likeValue)
                    ->orWhere('composer.artist_name', 'LIKE', $this->likeValue)
                    ->orWhere('arranger.artist_name', 'LIKE', $this->likeValue)
                    ->orWhere('wam.artist_name', 'LIKE', $this->likeValue)
                    ->orWhere('words.artist_name', 'LIKE', $this->likeValue)
                    ->orWhere('music.artist_name', 'LIKE', $this->likeValue)
                    ->orWhere('choreographer.artist_name', 'LIKE', $this->likeValue);
            })
            ->select('lib_stacks.id',
                'lib_items.id AS libItemId',
                'lib_titles.title', 'lib_titles.alpha', 'lib_items.item_type',
                'composer.alpha_name AS composerName',
                'arranger.alpha_name AS arrangerName',
                'wam.alpha_name AS wamName',
                'words.alpha_name AS wordsName',
                'music.alpha_name AS musicName',
                'choreographer.alpha_name AS choreographerName',
                'voicings.descr AS voicingDescr',
            )
            ->orderBy($this->sortCol, $this->sortAsc ? 'asc' : 'desc')
            ->orderBy('lib_titles.alpha', 'asc')
            ->get()
            ->toArray();
    }
}
