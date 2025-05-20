<?php

namespace App\Services\Libraries;

use App\Livewire\Forms\LibraryItemForm;
use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\Items\LibItem;
use Illuminate\Support\Facades\DB;

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
    }

    private function getItemIdsFromTitle(): void
    {
        //early exit
        if (!strlen($this->form->title)) {
            return;
        }

        $searchString = $this->buildSearchString($this->form->title);

        $titleIds = LibTitle::where('title', 'LIKE', $searchString)->pluck('id')->toArray();

        $this->itemIds = DB::table('lib_items')
            ->join('lib_titles', 'lib_titles.id', '=', 'lib_items.lib_title_id')
            ->leftJoin('artists as composer', 'composer.id', '=', 'lib_items.composer_id')
            ->leftJoin('artists as arranger', 'arranger.id', '=', 'lib_items.arranger_id')
            ->leftJoin('artists as wam', 'wam.id', '=', 'lib_items.wam_id')
            ->leftJoin('artists as words', 'words.id', '=', 'lib_items.words_id')
            ->leftJoin('artists as music', 'music.id', '=', 'lib_items.music_id')
            ->leftJoin('artists as choreographer', 'choreographer.id', '=', 'lib_items.choreographer_id')
            ->whereIn('lib_items.lib_title_id', $titleIds)
            ->select('lib_items.id',
                'composer.alpha_name as composer',
                'arranger.alpha_name as arranger',
                'wam.alpha_name as wam',
                'words.alpha_name as words',
                'music.alpha_name as music',
                'choreographer.alpha_name as choreographer',
                'lib_titles.title'
            )
            ->orderBy('lib_titles.title')
            ->orderBy('composer.alpha_name')
            ->orderBy('arranger.alpha_name')
            ->orderBy('wam.alpha_name')
            ->orderBy('words.alpha_name')
            ->orderBy('music.alpha_name')
            ->orderBy('choreographer.alpha_name')
            ->get()
            ->toArray();
    }

    private function buildArtistStack(\stdClass $item): string
    {
        $types = ['composer', 'arranger', 'wam', 'words', 'music', 'choreographer'];
        $str = '<div class="ml-2 text-xs">';

        foreach ($types as $type) {
            if ($item->$type) {
                $str .= "<div>".$item->$type."</div>";
            }
        }

        $str .= '</div>';

        return $str;
    }

    private function buildSearchString(string $string): string
    {
        return '%' . $string . '%';
    }

    private function setSearchResults(): string
    {
        $str = '<div><h3 class="underline">Search Results</h3>';

        if (count($this->itemIds)) {

            $str .= '<div class="text-sm ">';

            foreach ($this->itemIds as $itemId) {

                $artistStack = $this->buildArtistStack($itemId);
                $libItem = LibItem::find($itemId->id);
                $title = $itemId->title;
                $voicingDescr = $libItem->voicingDescr;

                $str .= "
                    <div class='text-left'>
                        <button wire:click='findItem($libItem->id)' class='px-1 ml-2 cursor-pointer rounded-lg w-11/12 mb-1 text-left hover:bg-gray-700 hover:text-white '>
                            <div class='uppercase bold'>$title ($voicingDescr)</div>
                            $artistStack
                        </button>
                    </div>";
            }

            $str .= '</div>';
        } else {
            $str .= '<div class="text-sm">None found.</div>';
        }

        $str .= '</div>';

        return $str;
    }

    public function getResults(): string
    {
        return $this->results;
    }
}
