<?php

namespace App\Livewire;

use App\Models\PageView;
use App\Models\Schools\School;
use Livewire\Component;

class BasePage extends Component
{
    public array $dto;
    public string $firstTimer = 'false';
    public bool $hasFilters = false;
    public bool $hasSearch = false;
    public string $header = 'header';
    public string $pageInstructions = "no instructions found...";
    public School $school;
    public string $schoolName = '';
    public array $schools = [];
    public bool $showSuccessIndicator = false;
    public string $successMessage = '';

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
