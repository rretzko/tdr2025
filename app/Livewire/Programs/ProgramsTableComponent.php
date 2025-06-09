<?php

namespace App\Livewire\Programs;

use App\Livewire\BasePage;
use App\Models\Programs\Program;
use App\Models\UserConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProgramsTableComponent extends BasePage
{
    public array $columnHeaders = [];
    public bool $displayForm = false;
    public int $schoolId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->columnHeaders = $this->getColumnHeaders();
        $this->schoolId = UserConfig::getValue('schoolId');
    }

    public function addNew(): void
    {
        $this->redirect(route('programs.new'));
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'year', 'sortBy' => 'year'],
            ['label' => 'title', 'sortBy' => 'title'],
            ['label' => 'perf.date', 'sortBy' => 'perf.date'],
            ['label' => 'tags', 'sortBy' => null],
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

    private function getRows(): Collection
    {
        return Program::where('school_id', $this->schoolId)
            ->orderBy('school_year', 'desc')
            ->orderBy('performance_date', 'desc')
            ->get();
    }
}
