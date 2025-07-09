<?php

namespace App\Traits\Libraries;

use App\Models\Libraries\Items\Components\LibItemLocation;
use App\Models\Libraries\Items\LibItem;
use App\Models\Libraries\LibStack;
use App\Models\Programs\Program;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait LibraryTableRowsTrait
{
    public static function getLibraryItems(
        int $libraryId,
        int $ensembleId = 0,
        string $searchValue = '',
        string $sortCol = 'lib_titles.title',
        bool $sortAsc = true,
    ): array {
        $searchFor = '%'.$searchValue.'%';

        return LibStack::query()
            ->join('lib_items', 'lib_stacks.lib_item_id', '=', 'lib_items.id')
            ->join('lib_titles', 'lib_items.lib_title_id', '=', 'lib_titles.id')
            ->leftJoin('artists AS composer', 'lib_items.composer_id', '=', 'composer.id')
            ->leftJoin('artists AS arranger', 'lib_items.arranger_id', '=', 'arranger.id')
            ->leftJoin('artists AS wam', 'lib_items.wam_id', '=', 'wam.id')
            ->leftJoin('artists AS words', 'lib_items.words_id', '=', 'words.id')
            ->leftJoin('artists AS music', 'lib_items.music_id', '=', 'music.id')
            ->leftJoin('artists AS choreographer', 'lib_items.choreographer_id', '=', 'choreographer.id')
            ->leftJoin('voicings', 'lib_items.voicing_id', '=', 'voicings.id')
            ->where('lib_stacks.library_id', $libraryId)
            ->when($ensembleId, function ($query) use ($ensembleId) {
                $query->join('program_selections', 'lib_stacks.lib_item_id', '=', 'program_selections.lib_item_id')
                    ->where('program_selections.ensemble_id', $ensembleId);
            })
            ->where(function ($query) use ($searchFor) {
                $query->where('lib_titles.title', 'LIKE', $searchFor)
                    ->orWhere('composer.artist_name', 'LIKE', $searchFor)
                    ->orWhere('arranger.artist_name', 'LIKE', $searchFor)
                    ->orWhere('wam.artist_name', 'LIKE', $searchFor)
                    ->orWhere('words.artist_name', 'LIKE', $searchFor)
                    ->orWhere('music.artist_name', 'LIKE', $searchFor)
                    ->orWhere('choreographer.artist_name', 'LIKE', $searchFor);
            })
            ->select('lib_stacks.id',
                'lib_items.id AS libItemId',
                'lib_titles.title', 'lib_titles.alpha', 'lib_items.item_type',
                'composer.alpha_name AS composerName',
                'arranger.alpha_name AS arrangerName',
                'wam.alpha_name AS wamName',
                'words.alpha_name AS wordsName',
                'music.alpha_name AS musicName',
                'choreographer.alpha_name AS choreographerName',
                'voicings.descr AS voicingDescr',
            )
            ->orderBy($sortCol, $sortAsc ? 'asc' : 'desc')
            ->orderBy('lib_titles.alpha', 'asc')
            ->get()
            ->toArray();
    }

    public static function getItemLocations(array $rows, int $libraryId): array
    {
        $locations = [];

        foreach ($rows as $row) {
            $libItemLocation = LibItemLocation::query()
                ->where('lib_item_id', $row['libItemId'])
                ->where('library_id', $libraryId)
                ->first();

            if ($libItemLocation) {

                $fLocation = LibItemLocation::query()
                    ->where('lib_item_id', $row['libItemId'])
                    ->where('library_id', $libraryId)
                    ->first()
                    ->formatLocation;
            } else {
                $fLocation = $row['libItemId'];
            }

            $locations[$row['libItemId']] = $fLocation;
        }

        return $locations;
    }

    public static function getItemPerformances(array $rows): array
    {
        $performances = [];

        foreach ($rows as $row) {
            $performances[$row['libItemId']] = Program::query()
                ->join('program_selections', 'program_selections.program_id', '=', 'programs.id')
                ->where('program_selections.lib_item_id', $row['libItemId'])
                ->pluck('programs.performance_date', 'programs.id')
                ->map(function ($date) {
                    return Carbon::parse($date)->format('M-y'); //ex. Jun-20
                })
                ->toArray();
        }

        return $performances;
    }

    public static function getItemTags(array $rows): array
    {
        $tags = [];

        foreach ($rows as $row) {
            $tags[$row['libItemId']] = LibItem::find($row['libItemId'])->tags()->pluck('name')->toArray();
        }

        return $tags;
    }
}
