<?php

namespace App\Livewire\Libraries\Items;

use App\Exports\LibraryItemsExport;
use App\Livewire\Libraries\LibraryBasePage;
use App\Models\Libraries\Items\Components\LibItemDoc;
use App\Models\Libraries\Items\Components\LibItemLocation;
use App\Models\Libraries\Items\Components\LibMedleySelection;
use App\Models\Libraries\Items\LibItem;
use App\Models\Libraries\LibLibrarian;
use App\Models\Libraries\Library;
use App\Models\Libraries\LibStack;
use App\Models\Programs\Program;
use App\Models\Programs\ProgramSelection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;
use App\Traits\Libraries\LibraryTableColumnHeadersTrait;
use App\Traits\Libraries\LibraryTableRowsTrait;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ItemTableComponent extends LibraryBasePage
{
    use LibraryTableColumnHeadersTrait;
    use LibraryTableRowsTrait;
    use WithPagination;

    public array $columnHeaders;
    public Library $library;
    public bool $displayForm = false;

    /**
     * @var bool
     * @todo this should be refactored up for dual use with EnsembleLibraryComponent
     */
    public bool $global = false;
    public string $globalSearch = '';

    public bool $hasSearch = true;

    public array $typeFilters = [];
    public int $typeFilterId = 0;

    public int $userId = 0;
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

        $this->userId = $this->getUserId();

        $this->typeFilters = $this->getTypeFilters();

        $this->recordsPerPage = 15;
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
            $this->typeFilters[$this->typeFilterId],
            $this->global,
        );
        $locations = $this->getItemLocations($rows, $this->library->id);
        $performances = $this->getItemPerformances($rows);
        $tags = $this->getItemTags($rows);
        $medleySelections = $this->getMedleySelections($rows);
        $voicings = $this->getVoicings($rows);
        $urls = $this->getItemUrls($rows);
        $docs = $this->getItemDocs($rows, $this->library->id, $this->userId, $this->global);

        $paginated = $this->getPaginatedData($rows);

        return view('livewire..libraries.items.item-table-component',
            [
                'rows' => $paginated,
                'locations' => $locations,
                'performances' => $performances,
                'tags' => $tags,
                'medleySelections' => $medleySelections,
                'voicings' => $voicings,
                'urls' => $urls,
                'docs' => $docs,
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

    public function export(): BinaryFileResponse|bool
    {
        $rows = $this->getLibraryItems(
            $this->library->id,
            0,
            '',
            $this->sortCol,
            $this->sortAsc,
            $this->voicingFilterId);

        $tags = $this->getItemTags($rows);

        $urls = $this->getItemUrls($rows);

        $perfs = $this->getItemPerformances($rows);

        $docs = $this->getItemDocs($rows, $this->library->id, $this->userId, false);

        $fileName = 'libraryItems_'.date('Ymd_His').'.csv';

        try {
            return Excel::download(new LibraryItemsExport($rows, $tags, $urls, $perfs, $docs), $fileName);
        } catch (Exception $e) {
            Log::info('*** '.$e->getMessage());
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            Log::info('*** PhpSpreadsheet exception: '.$e->getMessage());
        }

        return false;
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

    /**
     * @param  array  $dataArray
     * @return LengthAwarePaginator
     * @todo this should be refactored with same method in EnsembleLibraryComponent
     */
    private function getPaginatedData(array $dataArray): LengthAwarePaginator
    {
        $page = Paginator::resolveCurrentPage() ?: 1;

        $total = count($dataArray);

        $results = array_slice($dataArray, ($page - 1) * $this->recordsPerPage, $this->recordsPerPage);

        return new LengthAwarePaginator(
            $results,
            $total,
            $this->recordsPerPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
    }

    private function getTypeFilters(): array
    {
        return [
            'all',
            'paper',
            'recordings',
            'octavo',
            'medley',
            'digital',
            'cd',
            'dvd',
            'cassette',
            'vinyl'
        ];
    }

    private function getUserId(): int
    {
        if (auth()->user()->isLibrarian()) {
            return LibLibrarian::where('user_id', auth()->id())->first()->teacherUserId;
        }

        return auth()->id();
    }

    private function getVoicings(): array
    {
        $libStack = LibStack::where('library_id', $this->library->id)->first();

        return ($libStack)
            ? $libStack->voicingsArray
            : [];
    }
}
