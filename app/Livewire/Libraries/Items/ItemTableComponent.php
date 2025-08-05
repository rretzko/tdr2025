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
use Livewire\WithPagination;

class ItemTableComponent extends LibraryBasePage
{
    use LibraryTableColumnHeadersTrait;
    use LibraryTableRowsTrait;
    use WithPagination;

    public array $columnHeaders;
    public Library $library;
    public bool $displayForm = false;
    public string $globalSearch = '';

    public bool $hasSearch = true;

    public int $voicingFilterId = 0;

    public function mount(): void
    {
        parent::mount();

        $libraryId = auth()->user()->isLibrarian()
            ? LibLibrarian::where('user_id', auth()->id())->first()->library_id
            : $this->dto['id'];

        $this->library = Library::find($libraryId);

        $this->columnHeaders = $this->getColumnHeaders();

        $this->sortCol = 'lib_titles.alpha';
    }

    public function render()
    {
        $rows = $this->getLibraryItems(
            $this->library->id,
            0,
            $this->globalSearch,
            $this->sortCol,
            $this->sortAsc,
            $this->voicingFilterId,
        );
        $locations = $this->getItemLocations($rows, $this->library->id);
        $performances = $this->getItemPerformances($rows);
        $tags = $this->getItemTags($rows);
        $medleySelections = $this->getMedleySelections($rows);
        $voicings = $this->getVoicings($rows);

        return view('livewire..libraries.items.item-table-component',
            [
                'rows' => $rows,
                'locations' => $locations,
                'performances' => $performances,
                'tags' => $tags,
                'medleySelections' => $medleySelections,
                'voicings' => $voicings,
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

    public function updatedVoicingFilterId()
    {
        //re-render
    }

    private function canHaveSelections(string $itemType, $medleyTitles): bool
    {
        $hasSelections = ['medley', 'cd', 'dvd', 'cassette', 'vinyl'];
        if (in_array($itemType, $hasSelections)) {
            return true;
        }

        if (($itemType === 'book')
            && (!is_null($medleyTitles))
            && (strlen($medleyTitles) > 0)) {
            return true;
        }

        return false;
    }

    protected function getMedleySelections(array $rows): array
    {
        $selections = [];
        foreach ($rows as $row) {
            if ($this->canHaveSelections($row['item_type'], $row['medleyTitles'])) {
                $selections[$row['libItemId']] = LibMedleySelection::where('lib_item_id', $row['libItemId'])->get();
            }
        }

        return $selections;
    }

    private function getVoicings(): array
    {
        $libStack = LibStack::where('library_id', $this->library->id)->first();

        return ($libStack)
            ? $libStack->voicingsArray
            : [];
    }
}
