<?php

namespace App\Livewire\Events\Versions;

use App\Exports\VersionScoringExport;
use App\Livewire\BasePage;
use App\Livewire\Forms\VersionScoringForm;
use App\Models\Events\Event;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionScoring;
use App\Models\UserConfig;
use App\Services\FileTypesArrayService;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class VersionScoringTableComponent extends BasePage
{
    public VersionScoringForm $form;
    public array $fileTypes = [];
    public array $options0Thru50 = [];
    public array $options1Thru50 = [];
    public bool $showAddForm = false;
    public int $showEditForm = 0;
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->sortCol = 'version_scorings.order_by';
        $this->options0Thru50 = range(0, 50);
        $this->options1Thru50 = array_combine(range(1, 50), range(1, 50));
        $this->versionId = UserConfig::getValue('versionId');

        $this->fileTypes = FileTypesArrayService::getArray($this->versionId);

        $this->form->setDefaults($this->versionId, array_key_first($this->fileTypes));
    }

    public function render()
    {
        return view('livewire..events.versions.version-scoring-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
            ]);
    }

    private function clonePreviousVersionScorings(): Builder
    {
        $event = Event::find(UserConfig::getValue('eventId'));
        $versions = $event->versions;

        if ($versions->count() > 1) { //create db rows for $this->versionId using the previous scoring

            $version = $versions[1];

            $previousScorings = VersionScoring::query()
                ->where('version_id', 2) //$version->id));
                ->get();

            //use the previous scorings to create current models
            foreach ($previousScorings as $scoring) {

                VersionScoring::create(
                    [
                        'version_id' => $this->versionId,
                        'file_type' => $scoring->file_type,
                        'segment' => $scoring->segment,
                        'abbr' => $scoring->abbr,
                        'best' => $scoring->best,
                        'worst' => $scoring->worst,
                        'multiplier' => $scoring->multiplier,
                        'tolerance' => $scoring->tolerance,
                        'order_by' => $scoring->order_by,
                    ]
                );
            }

            //return a Builder object using the newly created rows
            return VersionScoring::query()
                ->where('version_id', $this->versionId)
                ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'));

        }
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'file type', 'sortBy' => null],
            ['label' => 'segment', 'sortBy' => null],
            ['label' => 'abbr', 'sortBy' => null],
            ['label' => 'order', 'sortBy' => null],
            ['label' => 'best', 'sortBy' => null],
            ['label' => 'worst', 'sortBy' => null],
            ['label' => 'multiplier', 'sortBy' => null],
            ['label' => 'tolerance', 'sortBy' => null],
        ];
    }

    private function getRows(): Builder
    {
        return (VersionScoring::query()
            ->where('version_id', $this->versionId)
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))->exists())
            ? VersionScoring::query()
                ->where('version_id', $this->versionId)
                ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            : $this->clonePreviousVersionScorings();
    }

    public function addSegment(): void
    {
        //ensure edit form is closed
        $this->reset('showEditForm', 'successMessage', 'showSuccessIndicator');
        $this->form->add();

        $this->showSuccessIndicator = true;

        $this->successMessage = 'Scoring segment has been added.';
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new VersionScoringExport, 'scoring.csv');
    }

    public function segmentUpdate()
    {
        $this->form->segmentUpdate();

        $this->reset('showEditForm');

        $this->showSuccessIndicator = true;

        $this->successMessage = 'Scoring segment has been updated.';
    }

    public function updatedShowEditForm(): void
    {
        //ensure add form is closed
        $this->reset('showAddForm', 'successMessage', 'showSuccessIndicator');

        $this->form->setEditValues($this->showEditForm);
    }

    public function updatedShowAddForm(): void
    {
        //ensure $showEditForm is closed
        $this->reset('showEditForm', 'successMessage', 'showSuccessIndicator');
    }
}
