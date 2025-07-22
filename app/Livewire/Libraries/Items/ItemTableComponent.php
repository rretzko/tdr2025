<?php

namespace App\Livewire\Libraries\Items;

use App\Livewire\Libraries\LibraryBasePage;
use App\Models\Libraries\Items\Components\LibItemLocation;
use App\Models\Libraries\Items\Components\LibMedleySelection;
use App\Models\Libraries\Items\LibItem;
use App\Models\Libraries\LibLibrarian;
use App\Models\Libraries\Library;
use App\Models\Libraries\LibStack;
use App\Models\Programs\Program;
use App\Models\Programs\ProgramSelection;
use Carbon\Carbon;
use JetBrains\PhpStorm\NoReturn;
use App\Traits\Libraries\LibraryTableColumnHeadersTrait;
use App\Traits\Libraries\LibraryTableRowsTrait;

class ItemTableComponent extends LibraryBasePage
{
    use LibraryTableColumnHeadersTrait;
    use LibraryTableRowsTrait;

    public array $columnHeaders;
    public Library $library;
    public bool $displayForm = false;
    public string $globalSearch = '';
//    public string $likeValue = ''; //wrap $globalSearch in %%
    public bool $hasSearch = true;

    public function mount(): void
    {
        parent::mount();

        $libraryId = auth()->user()->isLibrarian()
            ? LibLibrarian::where('user_id', auth()->id())->first()->library_id
            : $this->dto['id'];

        $this->library = Library::find($libraryId);

        $this->columnHeaders = $this->getColumnHeaders();

        $this->sortCol = 'lib_titles.alpha';

//        $this->updatedGlobalSearch(); //set initial $this->likeValue to "%%"
    }

    public function render()
    {
        $rows = $this->getLibraryItems(
            $this->library->id,
            0,
            $this->globalSearch,
            $this->sortCol,
            $this->sortAsc,
        );
        $locations = $this->getItemLocations($rows, $this->library->id);
        $performances = $this->getItemPerformances($rows);
        $tags = $this->getItemTags($rows);
        $medleySelections = $this->getMedleySelections($rows);

        return view('livewire..libraries.items.item-table-component',
            [
                'rows' => $rows,
                'locations' => $locations,
                'performances' => $performances,
                'tags' => $tags,
                'medleySelections' => $medleySelections,
            ]
        );
    }

    /**
     * @return null
     */
    public function clickForm()
    {
        $libraryId = $this->library->id;
        return $this->redirect("/library/{$libraryId}/item/new");
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

//    public function updatedGlobalSearch(): void
//    {
//        $this->likeValue = '%'.$this->globalSearch.'%';
//    }

//    private function getLibItemLocations(array $rows): array
//    {
//        $locations = [];
//
//        foreach ($rows as $row) {
//            $libItemLocation = LibItemLocation::query()
//                ->where('lib_item_id', $row['libItemId'])
//                ->where('library_id', $this->library->id)
//                ->first();
//
//            if($libItemLocation) {
//
//                $fLocation = LibItemLocation::query()
//                    ->where('lib_item_id', $row['libItemId'])
//                    ->where('library_id', $this->library->id)
//                    ->first()
//                    ->formatLocation;
//            }else{
//                $fLocation = $row['libItemId'];
//            }
//
//            $locations[$row['libItemId']] = $fLocation;
//        }
//
//        return $locations;
//    }

//    private function getPerformances(array $rows): array
//    {
//        $performances = [];
//
//        foreach ($rows as $row) {
//            $performances[$row['libItemId']] = Program::query()
//                ->join('program_selections', 'program_selections.program_id', '=', 'programs.id')
//                ->where('program_selections.lib_item_id', $row['libItemId'])
//                ->pluck('programs.performance_date', 'programs.id')
//                ->map(function ($date) {
//                    return Carbon::parse($date)->format('M-y'); //ex. Jun-20
//                })
//                ->toArray();
//        }
//
//        return $performances;
//    }

//    private function getTags(array $rows): array
//    {
//        $tags = [];
//
//        foreach ($rows as $row) {
//            $tags[$row['libItemId']] = LibItem::find($row['libItemId'])->tags()->pluck('name')->toArray();
//        }
//
//        return $tags;
//    }

//    private function getRows(): array
//    {
//        return LibStack::query()
//            ->join('lib_items', 'lib_stacks.lib_item_id', '=', 'lib_items.id')
//            ->join('lib_titles', 'lib_items.lib_title_id', '=', 'lib_titles.id')
//            ->leftJoin('artists AS composer', 'lib_items.composer_id', '=', 'composer.id')
//            ->leftJoin('artists AS arranger', 'lib_items.arranger_id', '=', 'arranger.id')
//            ->leftJoin('artists AS wam', 'lib_items.wam_id', '=', 'wam.id')
//            ->leftJoin('artists AS words', 'lib_items.words_id', '=', 'words.id')
//            ->leftJoin('artists AS music', 'lib_items.music_id', '=', 'music.id')
//            ->leftJoin('artists AS choreographer', 'lib_items.choreographer_id', '=', 'choreographer.id')
//            ->leftJoin('voicings', 'lib_items.voicing_id', '=', 'voicings.id')
//            ->leftJoin('taggables', 'lib_items.id', '=', 'taggables.taggable_id')
//            ->leftJoin('tags', 'taggables.tag_id', '=', 'tags.id')
//            ->where('lib_stacks.library_id', $this->library->id)
//            ->where(function ($query) {
//                $query->where('lib_titles.title', 'LIKE', $this->likeValue)
//                    ->orWhere('composer.artist_name', 'LIKE', $this->likeValue)
//                    ->orWhere('arranger.artist_name', 'LIKE', $this->likeValue)
//                    ->orWhere('wam.artist_name', 'LIKE', $this->likeValue)
//                    ->orWhere('words.artist_name', 'LIKE', $this->likeValue)
//                    ->orWhere('music.artist_name', 'LIKE', $this->likeValue)
//                    ->orWhere('choreographer.artist_name', 'LIKE', $this->likeValue)
//                    ->orWhere('tags.name', 'LIKE', $this->likeValue);
//            })
//            ->distinct()
//            ->select('lib_stacks.id',
//                'lib_items.id AS libItemId',
//                'lib_titles.title', 'lib_titles.alpha', 'lib_items.item_type',
//                'composer.alpha_name AS composerName',
//                'arranger.alpha_name AS arrangerName',
//                'wam.alpha_name AS wamName',
//                'words.alpha_name AS wordsName',
//                'music.alpha_name AS musicName',
//                'choreographer.alpha_name AS choreographerName',
//                'voicings.descr AS voicingDescr',
//            )
//            ->orderBy($this->sortCol, $this->sortAsc ? 'asc' : 'desc')
//            ->orderBy('lib_titles.alpha', 'asc')
//            ->get()
//            ->toArray();
//    }
    protected function getMedleySelections(array $rows): array
    {
        $selections = [];
        foreach ($rows as $row) {
            if ($row['item_type'] === 'medley') {
                $selections[$row['libItemId']] = LibMedleySelection::where('lib_item_id', $row['libItemId'])->get();
            }
        }

        return $selections;
    }
}
