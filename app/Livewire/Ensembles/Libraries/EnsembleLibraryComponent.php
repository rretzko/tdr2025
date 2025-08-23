<?php

namespace App\Livewire\Ensembles\Libraries;

use App\Livewire\Forms\EnsembleLibraryForm;
use App\Livewire\Libraries\LibraryBasePage;
use App\Models\Ensembles\Ensemble;
use App\Models\Libraries\Items\Components\LibItemLocation;
use App\Models\Libraries\Items\Components\LibMedleySelection;
use App\Models\Libraries\Items\LibItem;
use App\Models\Libraries\LibLibrarian;
use App\Models\Libraries\Library;
use App\Models\Programs\Program;
use App\Models\Schools\Teacher;
use App\Models\UserConfig;
use App\Services\CoTeachersService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\Libraries\LibraryTableColumnHeadersTrait;
use App\Traits\Libraries\LibraryTableRowsTrait;

class EnsembleLibraryComponent extends LibraryBasePage
{
    use LibraryTableColumnHeadersTrait;
    use LibraryTableRowsTrait;

    public EnsembleLibraryForm $form;
    public array $columnHeaders = [];
    public bool $displayForm = false;
    public int $ensembleId = 0;
    public array $ensembles = [];
    /**
     * @var bool
     * @todo this should be refactored up for dual use with ItemTableComponent
     */
    public bool $global = false;
    public Library $library;
    public int $libraryId = 0;
    public array $programs = [];
    public string $searchTitle = '';
    public string $searchValue = '';
    public array $statuses = [];
    public int $userId = 0;

    public function mount(): void
    {
        parent::mount();

        $coteachers = CoTeachersService::getCoTeachersIds();

        $this->ensembles = Ensemble::whereIn('teacher_id', $coteachers)
            ->select('id', 'abbr', 'name')
            ->orderBy('abbr')
            ->get()
            ->toArray();

        //test if any ensembles have been registered
        $this->ensembleId = array_key_exists(0, $this->ensembles) ? $this->ensembles[0]['id'] : 0;

        $teacherId = Teacher::where('user_id', auth()->id())->first()->id;

        $this->library = Library::query()
            ->where('school_id', UserConfig::getValue('schoolId'))
            ->where('teacher_id', $teacherId)
            ->first();

        $this->libraryId = $this->library->id;

        $this->programs = ['rehearsal-only', 'warm-ups'];
        $this->statuses = ['pulled', 'rehearsed', 'programmed', 'returned'];

        $this->columnHeaders = $this->getColumnHeaders();

        $this->sortCol = 'lib_titles.title';
        $this->sortColLabel = 'title';
        $this->sortAsc = true;

        $this->userId = $this->getUserId();
    }

    public function render()
    {
        $this->searchValue = $this->getSearchValue();
        $rows = $this->getRows();
        $locations = $this->getItemLocations($rows, $this->libraryId);
        $performances = $this::getItemPerformances($rows);
        $tags = $this->getItemTags($rows);
        $docs = $this->getItemDocs($rows, $this->libraryId, $this->userId, false);
        $urls = $this->getItemUrls($rows);
        $medleySelections = $this->getMedleySelections($rows);

        $paginated = $this->getPaginatedData($rows);

        return view('livewire..ensembles.libraries.ensemble-library-component',
            [
                'performances' => $performances,
                'rows' => $paginated, //$rows,
                'titleSearchResults' => $this->getTitleSearchResults(),
                'itemsToPullCount' => count($this->itemsToPull),
                'locations' => $locations,
                'tags' => $tags,
                'docs' => $docs,
                'urls' => $urls,
                'medleySelections' => $medleySelections,
            ]);
    }


    public function clickForm(): void
    {
        $this->displayForm = !$this->displayForm;
    }

    public function updatedEnsembleId(): void
    {
        $this->reset('displayForm');
    }

    public function setItem(int $libItemsId): void
    {
        $this->form->libItemsId = $libItemsId;
    }

    public function sortBy(string $value): void
    {
        $map = [
            'type' => 'lib_items.item_type',
            'title' => 'lib_titles.title',
            'voicing' => 'voicings.descr',
        ];

        $this->sortColLabel = $value;

        $this->sortCol = $map[$value];
        $this->sortAsc = !$this->sortAsc;
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

    private function getUserId(): int
    {
        if (auth()->user()->isLibrarian()) {
            return LibLibrarian::where('user_id', auth()->id())->first()->teacherUserId;
        }

        return auth()->id();
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

    /**
     * @param  array  $dataArray
     * @return LengthAwarePaginator
     * @todo this should be refactored with same method in ItemTableComponent
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

    private function getRows(): array
    {
        //early exit
        if (!$this->ensembleId) {
            return [];
        }

        return $this::getLibraryItems(
            $this->libraryId,
            $this->ensembleId,
            $this->searchValue,
            $this->sortCol,
            $this->sortAsc,

        );
    }

    private function getSearchValue(): string
    {
        return '';
    }

    private function getTitleSearchResults(): array
    {
        //early exit
        if (!$this->form->title) {
            return [];
        }

        $coteachers = CoTeachersService::getCoTeachersIds();
        $ensemble = Ensemble::find($this->ensembleId);
        $schoolId = $ensemble->school_id;

        $libraryId = Library::query()
            ->where('school_id', $schoolId)
            ->whereIn('teacher_id', $coteachers)
            ->first()
            ->id ?? 0;
        $searchValue = '%'.$this->form->title.'%';

        if (strlen($searchValue) > 3) {
            Log::info(DB::table('lib_stacks')
                ->join('lib_items', 'lib_items.id', '=', 'lib_stacks.lib_item_id')
                ->join('lib_titles', 'lib_titles.id', '=', 'lib_items.lib_title_id')
                ->where('lib_stacks.library_id', $libraryId)
                ->where('lib_titles.title', 'LIKE', $searchValue)
                ->select('lib_titles.title', 'lib_items.id')
                ->orderBy('lib_titles.title')
                ->toRawSql());
        }
        return DB::table('lib_stacks')
            ->join('lib_items', 'lib_items.id', '=', 'lib_stacks.lib_item_id')
            ->join('lib_titles', 'lib_titles.id', '=', 'lib_items.lib_title_id')
            ->where('lib_stacks.library_id', $libraryId)
            ->where('lib_titles.title', 'LIKE', $searchValue)
            ->select('lib_titles.title', 'lib_items.id')
            ->orderBy('lib_titles.title')
            ->get()
            ->toArray();

    }

}
