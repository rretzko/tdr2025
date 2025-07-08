<?php

namespace App\Livewire\Ensembles\Libraries;

use App\Livewire\Forms\EnsembleLibraryForm;
use App\Livewire\Libraries\LibraryBasePage;
use App\Models\Ensembles\Ensemble;
use App\Models\Libraries\Library;
use App\Models\Programs\Program;
use App\Models\Schools\Teacher;
use App\Models\UserConfig;
use App\Services\CoTeachersService;
use Carbon\Carbon;
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
    public int $libraryId = 0;
    public array $programs = [];
    public string $searchTitle = '';
    public string $searchValue = '';
    public array $statuses = [];

    public function mount(): void
    {
        parent::mount();

        $coteachers = CoTeachersService::getCoTeachersIds();

        $this->ensembles = Ensemble::whereIn('teacher_id', $coteachers)
            ->select('id', 'abbr', 'name')
            ->orderBy('abbr')
            ->get()
            ->toArray();

        $this->ensembleId = 3;// $this->ensembles[0]['id'];

        $teacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $this->libraryId = Library::query()
            ->where('school_id', UserConfig::getValue('schoolId'))
            ->where('teacher_id', $teacherId)
            ->first()->id;

        $this->programs = ['rehearsal-only', 'warm-ups'];
        $this->statuses = ['pulled', 'rehearsed', 'programmed', 'returned'];

        $this->columnHeaders = $this->getColumnHeaders();

        $this->sortCol = 'lib_titles.title';
        $this->sortColLabel = 'title';
        $this->sortAsc = true;
    }

    public function render()
    {
        $this->searchValue = $this->getSearchValue();
        $rows = $this->getRows();
        $performances = $this::getItemPerformances($rows);

        return view('livewire..ensembles.libraries.ensemble-library-component',
            [
                'performances' => $performances,
                'rows' => $rows,
                'titleSearchResults' => $this->getTitleSearchResults(),
                'itemsToPullCount' => count($this->itemsToPull),
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

    private function getRows(): array
    {
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
