<?php

namespace App\Livewire;

use App\Models\PageView;
use App\Models\Schools\School;
use App\Models\UserConfig;
use Livewire\Component;
use Livewire\WithPagination;

class BasePage extends Component
{
    use WithPagination;

    public array $dto;
    public Filters $filters;
    public string $firstTimer = 'false';
    public bool $hasFilters = false;
    public bool $hasSearch = false;
    public string $header = 'header';
    public string $pageInstructions = "no instructions found...";
    public int $recordsPerPage = 15;
    public School $school;
    public string $schoolName = '';
    public array $schools = [];
    public string $search = '';
    public bool $showSuccessIndicator = false;
    public bool $sortAsc = true;
    public string $sortCol = '';
    public string $sortColLabel = '';
    public string $successMessage = '';

    public const ENSEMBLETABS = ['ensembles', 'members', 'assets', 'inventory'];

    public function mount(): void
    {
        $this->header = $this->dto['header'];
        $this->pageInstructions = $this->dto['pageInstructions'];
        $this->setFirstTimer($this->dto['header']);

        $this->schools = auth()->user()->teacher->schools
            ->sortBy('name')
            ->pluck('name', 'id')
            ->toArray();

        $this->school = (count($this->schools) === 1)
            ? auth()->user()->teacher->schools->first()
            : new School();

        $this->schoolName = ($this->school->id)
            ? $this->school->name
            : '';

        $this->filters->init($this->dto['header']);

        $this->recordsPerPage = UserConfig::query()
            ->where('user_id', auth()->id())
            ->where('header', $this->dto['header'])
            ->where('property', 'recordsPerPage')
            ->value('value') ?? 15;
    }

    public function updatedRecordsPerPage(): void
    {
        UserConfig::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'header' => $this->dto['header'],
                'property' => 'recordsPerPage',
            ],
            [
                'value' => $this->recordsPerPage,
            ]
        );
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    protected function setFirstTimer($header): void
    {
        $pageView = PageView::firstOrCreate(
            [
                'header' => $header,
                'user_id' => auth()->id(),
            ],
            [
                'view_count' => 0,
            ]
        );

        $this->firstTimer = ($pageView->view_count) ? 'false' : 'true';

        $pageView->update([
            'view_count' => ($pageView->view_count + 1)
        ]);
    }

    protected function getHeader(array $dto): void
    {
        $this->header = $dto['header'];
    }

    protected function getPageInstructions(array $dto): void
    {
        $this->pageInstructions = $dto['instructions'];
    }

}
