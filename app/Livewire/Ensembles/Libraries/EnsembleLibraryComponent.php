<?php

namespace App\Livewire\Ensembles\Libraries;

use App\Livewire\BasePage;
use App\Livewire\Forms\EnsembleLibraryForm;
use App\Models\Ensembles\Ensemble;
use App\Models\Libraries\Library;
use App\Services\CoTeachersService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnsembleLibraryComponent extends BasePage
{
    public EnsembleLibraryForm $form;
    public array $columnHeaders = [];
    public bool $displayForm = true; //false;
    public int $ensembleId = 0;
    public array $ensembles = [];
    public array $programs = [];
    public string $searchTitle = '';
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

        $this->ensembleId = $this->ensembles[0]['id'];

        $this->programs = ['rehearsal-only', 'warm-ups'];
        $this->statuses = ['pulled', 'rehearsed', 'programmed', 'returned'];


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

    public function render()
    {
        return view('livewire..ensembles.libraries.ensemble-library-component',
            [
                'rows' => [],
                'titleSearchResults' => $this->getTitleSearchResults(),
            ]);
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
