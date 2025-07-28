<?php

namespace App\Livewire\Programs;

use App\Livewire\BasePage;
use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\Items\LibItem;
use App\Models\Programs\Program;
use App\Models\Programs\ProgramSelection;
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

    public string $titleSearchResults = '';

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
        $this->titleSearchResults = $this->setTitleSearchResults();
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
            ['label' => 'songs', 'sortBy' => null],
            ['label' => 'tags', 'sortBy' => null],
        ];
    }

    private function getMatchingSongTitles(int $programId, string $songTitle): array
    {
        Log::info(
            Program::query()
                ->join('program_selections', 'programs.id', '=', 'program_selections.program_id')
                ->join('lib_items', 'program_selections.lib_item_id', '=', 'lib_items.id')
                ->join('lib_titles', 'lib_items.lib_title_id', '=', 'lib_titles.id')
                ->where('programs.id', $programId)
                ->where('lib_titles.title', 'LIKE', '%'.$songTitle.'%')
                ->select('lib_titles.title')
                ->toRawSql()
        );
        return Program::query()
            ->join('program_selections', 'programs.id', '=', 'program_selections.program_id')
            ->join('lib_items', 'program_selections.lib_item_id', '=', 'lib_items.id')
            ->join('lib_titles', 'lib_items.lib_title_id', '=', 'lib_titles.id')
            ->where('programs.id', $programId)
            ->where('lib_titles.title', 'LIKE', '%'.$songTitle.'%')
            ->pluck('lib_titles.title')
            ->toArray();

    }

    private function getProgramIdsFromLibItemIds($libItemIds): array
    {
        return ProgramSelection::query()
            ->whereIn('lib_item_id', $libItemIds)
            ->distinct()
            ->pluck('program_id')
            ->toArray();
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

        //isolate song title (value between quotes)
        $songFragment = $this->parseSearchForSongTitle();

        //find the libItem->id for the requested song title
        $libItemIds = (strlen($songFragment))
            ? $this->parseSearchForLibItemIds($songFragment)
            : [];

        $songTitleProgramIds = $this->getProgramIdsFromLibItemIds($libItemIds);

        //remove song title (if any) from $this->search string
        $search = (strlen($songFragment))
            ? $this->removeSongTitleFromSearch()
            : $this->search;

        //isolate school_years from remaining $search string
        $years = $this->parseSearchForSchoolYears($search);

        //isolate tags from individual words in remaining $search string
        $tags = $this->parseSearchForTags($search);

        return Program::query()
            ->whereIn('school_id', $this->filters->schoolsSelectedIds)
            ->when(count($songTitleProgramIds), function ($query) use ($songTitleProgramIds) {
                $query->whereIn('id', $songTitleProgramIds);
            })
            ->where(function ($query) use ($search, $tags, $years, $songTitleProgramIds) {
                $query->whereIn('school_year', $years)
                    ->orWhere('title', 'like', "%$search%")
                    ->orWhereHas('tags', function ($q) use ($tags) {
                        $q->whereIn('name', $tags);
                    });
            })
            ->orderBy($this->primarySort, $direction)
            ->orderBy($secondarySort, $secondarySortOrder)
            ->get();
    }

    private function parseSearchForLibItemIds(string $songFragment): array
    {
//        $libTitleIds = LibTitle::query()
//        ->where('title', 'like', "%$songTitle%")
//        ->pluck('id')
//        ->toArray();

        return LibItem::query()
            ->join('lib_titles', 'lib_items.lib_title_id', '=', 'lib_titles.id')
            ->where('lib_titles.title', 'like', "%$songFragment%")
//            ->whereIn('lib_title_id', $libTitleIds)
            ->pluck('lib_items.id')
            ->toArray();
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

    private function parseSearchForSchoolYears(string $search): array
    {
        $parts = $this->parseSearchForTags($search);
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

    private function parseSearchForTags(string $search): array
    {
        if (strlen($search)) {
            return explode(' ', $search);
        }

        return [];
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

    private function setTitleSearchResults(): string
    {
        $results = '';
        //isolate the song title from double-quotes
        $songFragment = $this->parseSearchForSongTitle();

        //find the libItem->id for the requested song title
        if ($songFragment) {
            $libItemIds = (strlen($songFragment))
                ? $this->parseSearchForLibItemIds($songFragment)
                : [];

            if ($libItemIds) {
                $songTitleProgramIds = $this->getProgramIdsFromLibItemIds($libItemIds);

                if ($songTitleProgramIds) {
                    $results .= '<style>';
                    $results .= '#foundSongsTable td,th{border: 1px solid #ddd; padding: 0 0.25rem;}';
                    $results .= '</style>';
                    $results .= '<table id="foundSongsTable" class="text-sm my-2 shadow-lg">';
                    $results .= '<thead>';
                    $results .= '<tr><th colspan="3">Song Title Search Results</th></tr>';
                    $results .= '<tr><th>program</th><th>schoolYr</th><th>song title(s)</th></tr>';
                    $results .= '</thead>';
                    foreach ($songTitleProgramIds as $programId) {
                        $program = Program::find($programId);
                        $results .= '<tr>';
                        $results .= '<td class="px-2">'.$program->title.'</td>';
                        $results .= '<td class="px-2 text-center">'.$program->school_year.'</td>';
                        $results .= '<td class="px-2">';
                        $matchingSongTitles = $this->getMatchingSongTitles($programId, $songFragment);
                        foreach ($matchingSongTitles as $songTitle) {
                            $results .= '<div class="px-2">'.$songTitle.'</div>';
                        }
                        $results .= '</td>';
                        $results .= '</tr>';
                    }
                    $results .= '</table>';
                }
            }
        }

        return $results;
    }
}
