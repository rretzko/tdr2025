<?php

namespace App\Livewire\Programs;

use App\Livewire\BasePage;
use App\Models\Programs\Program;
use App\Models\UserConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProgramsTableComponent extends BasePage
{
    public array $columnHeaders = [];
    public bool $displayForm = false;
    public string $primarySort = '';

    public int $schoolId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->columnHeaders = $this->getColumnHeaders();
        $this->schoolId = UserConfig::getValue('schoolId');

        //filters
        $this->hasFilters = true;
        if (empty($this->filters->schoolsSelectedIds)) {
            $this->filters->schoolsSelectedIds[] = $this->schoolId;
        }

        //sorts
        $this->sortColLabel = 'schoolYear';
        $this->primarySort = 'school_year';
        $this->sortAsc = false;
    }

    public function addNew(): void
    {
        $this->redirect(route('programs.new'));
    }

    public function edit(int $programId): void
    {
        $this->redirect(route('programs.edit', $programId));
    }

    public function remove(int $programId): void
    {
        $program = Program::find($programId);
        $program->tags()->detach();
        $program->delete();
    }

    public function sortBy(string $sortBy): void
    {
        $map = [
            'perf_date' => 'performance_date',
            'title' => 'title',
            'year' => 'school_year',
        ];

        $this->sortColLabel = $sortBy;

        $this->primarySort = $map[$sortBy];
        $this->sortAsc = !$this->sortAsc;

    }

    public function view(int $programId): void
    {
        $this->redirect(route('programs.show', $programId));
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'schoolYr', 'sortBy' => 'year'],
            ['label' => 'title', 'sortBy' => 'title'],
            ['label' => 'perf.date', 'sortBy' => 'perf_date'],
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
        $direction = $this->sortAsc ? 'asc' : 'desc';

        //performance_date descending is the standard secondary sort EXCEPT
        //when $this->primarySort === 'performance_date'.
        //If $this->primarySort === 'performnace_date', the secondary sort will
        //mimic the primarySort
        $secondarySort = 'performance_date';
        $secondarySortOrder = 'desc';
        if ($this->primarySort === 'performance_date') {
            $secondarySort = $this->primarySort;
            $secondarySortOrder = $direction;
        }

        return Program::query()
            ->where('school_id', $this->schoolId)
            ->whereIn('school_id', $this->filters->schoolsSelectedIds)
            ->orderBy($this->primarySort, $direction)
            ->orderBy($secondarySort, $secondarySortOrder)
            ->get();
    }
}
