<?php

namespace App\Livewire\Students;

use App\Livewire\BasePage;
use Livewire\Component;

class StudentsTableComponent extends BasePage
{
    public function mount(): void
    {
        parent::mount();
        $this->hasFilters = true;
        $this->hasSearch = true;
    }

    public function render()
    {
        return view('livewire..students.students-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows(),
            ]);
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function getColumnHeaders(): array
    {
        return [
            'name',
            'class of',
            'height',
            'birthday',
            'shirt size',
        ];
    }

    private function getRows(): array
    {
        return [];
    }
}
