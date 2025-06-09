<?php

namespace App\Livewire\Programs;

use App\Livewire\BasePage;

class ProgramsTableComponent extends BasePage
{
    public array $columnHeaders = [];
    public bool $displayForm = false;

    public function mount(): void
    {
        parent::mount();

        $this->columnHeaders = $this->getColumnHeaders();
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'year', 'sortBy' => 'year'],
            ['label' => 'program', 'sortBy' => 'program'],
        ];
    }

    public function render()
    {
        return view('livewire..programs.programs-table-component',
            [
                'rows' => $this->getRows(),
            ]

        );
    }

    private function getRows(): array
    {
        return [];
    }
}
