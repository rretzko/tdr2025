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

        //search
        $this->hasSearch = true;
    }

    public function render()
    {
        Log::info('searchValue: '.$this->search);
        return view('livewire..programs.programs-table-component',
            [
                'rows' => $this->getRows(),
            ]
        );
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

    public function updateSearchCriteria(): void
    {
        $this->getRows();
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

    private function getRows(): Collection
    {
        //primary sort direction
        $direction = $this->sortAsc ? 'asc' : 'desc';

        //secondary sort & secondary sort direction
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

        //search
        $search = $this->search
            ? "%{$this->search}%"
            : "%%";

        $songTitle = $this->parseSearchForSongTitle();
        $search = (strlen($songTitle))
            ? $this->removeSongTitleFromSearch()
            : $this->search;
        $years = $this->parseSearchForSchoolYears($search);
        $tags = $this->parseSearchForTags($search);

        return Program::query()
            ->where('school_id', $this->schoolId)
            ->whereIn('school_id', $this->filters->schoolsSelectedIds)
            ->where(function ($query) use ($search, $tags, $years) {
                $query->whereIn('school_year', $years)
                    ->orWhere('title', 'like', $search)
                    ->orWhereHas('tags', function ($q) use ($tags) {
                        $q->whereIn('name', $tags);
                    });
            })
            ->orderBy($this->primarySort, $direction)
            ->orderBy($secondarySort, $secondarySortOrder)
            ->get();
    }

    private function parseSearchForSongTitle(): string
    {
        // Extract the value between quotes
        preg_match('/"([^"]*)"/', $this->search, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        }

        return '';

    }

    private function parseSearchForSchoolYears(): array
    {
        $parts = $this->parseSearchForTags();
        $years = [];
        foreach ($parts as $part) {

            if ((strlen($part) === 4) &&
                is_numeric($part[0]) &&
                ($part >= 1960) &&
                ($part <= 2099)
            ) {
                $years[] = $part;
            }

        }

        return $years;
    }

    private function parseSearchForTags(): array
    {
        return explode(' ', $this->search);
    }

    private function removeSongTitleFromSearch(): string
    {
        // Extract the value between quotes
        preg_match('/"([^"]*)"/', $this->search, $matches);

        if (isset($matches[1])) {

            // Remove the quoted part (including quotes) from the original string
            $strWithoutQuotes = preg_replace('/"[^"]*"/', '', $this->search);

            return trim($strWithoutQuotes);
        }

        return '';
    }
}
