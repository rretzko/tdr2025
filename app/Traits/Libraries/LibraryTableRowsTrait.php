<?php

namespace App\Traits\Libraries;

use App\Models\Libraries\Items\Components\LibDigital;
use App\Models\Libraries\Items\Components\LibItemDoc;
use App\Models\Libraries\Items\Components\LibItemLocation;
use App\Models\Libraries\Items\LibItem;
use App\Models\Libraries\LibStack;
use App\Models\Programs\Program;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * select distinct `lib_stacks`.`id`, `lib_stacks`.`count`, `lib_items`.`id` as `libItemId`, `lib_titles`.`title`, `lib_titles`.`alpha`, `lib_items`.`item_type`,
 * `composer`.`alpha_name` as `composerName`,
 * `arranger`.`alpha_name` as `arrangerName`,
 * `wam`.`alpha_name` as `wamName`,
 * `words`.`alpha_name` as `wordsName`,
 * `music`.`alpha_name` as `musicName`,
 * `choreographer`.`alpha_name` as `choreographerName`, `
 * author`.`alpha_name` as `authorName`,
 * `voicings`.`descr` as `voicingDescr`,
 * GROUP_CONCAT(DISTINCT medley_titles.title ORDER BY medley_titles.alpha SEPARATOR ", ") AS medleyTitles
 * from `lib_stacks`
 * inner join `lib_items` on `lib_stacks`.`lib_item_id` = `lib_items`.`id`
 * inner join `lib_titles` on `lib_items`.`lib_title_id` = `lib_titles`.`id`
 * left join `artists` as `composer` on `lib_items`.`composer_id` = `composer`.`id`
 * left join `artists` as `arranger` on `lib_items`.`arranger_id` = `arranger`.`id`
 * left join `artists` as `wam` on `lib_items`.`wam_id` = `wam`.`id`
 * left join `artists` as `words` on `lib_items`.`words_id` = `words`.`id`
 * left join `artists` as `music` on `lib_items`.`music_id` = `music`.`id`
 * left join `artists` as `choreographer` on `lib_items`.`choreographer_id` = `choreographer`.`id`
 * left join `artists` as `author` on `lib_items`.`author_id` = `author`.`id`
 * left join `voicings` on `lib_items`.`voicing_id` = `voicings`.`id`
 * left join `taggables` on `lib_items`.`id` = `taggables`.`taggable_id`
 * left join `tags` on `taggables`.`tag_id` = `tags`.`id`
 * left join `lib_medley_selections` on `lib_items`.`id` = `lib_medley_selections`.`lib_item_id`
 * left join `lib_titles` as `medley_titles` on `lib_medley_selections`.`lib_title_id` = `medley_titles`.`id`
 * where `lib_stacks`.`library_id` = 2
 * and (`lib_items`.`voicing_id` > 0 or `lib_items`.`voicing_id` is null)
 * and (`lib_titles`.`title` LIKE '%%'
 * or `composer`.`artist_name` LIKE '%%'
 * or `arranger`.`artist_name` LIKE '%%'
 * or `wam`.`artist_name` LIKE '%%'
 * or `words`.`artist_name` LIKE '%%'
 * or `music`.`artist_name` LIKE '%%'
 * or `choreographer`.`artist_name` LIKE '%%'
 * or `author`.`artist_name` LIKE '%%'
 * or `tags`.`name` LIKE '%%'
 * or `medley_titles`.`title` LIKE '%%')
 * group by `lib_stacks`.`id`,
 * `lib_titles`.`title`,
 * `lib_titles`.`alpha`,
 * `lib_items`.`item_type`,
 * `composer`.`alpha_name`,
 * `arranger`.`alpha_name`,
 * `wam`.`alpha_name`,
 * `words`.`alpha_name`,
 * `music`.`alpha_name`,
 * `choreographer`.`alpha_name`,
 * `author`.`alpha_name`,
 * `voicingDescr`,
 * `lib_items`.`id`,
 * `lib_stacks`.`count`
 * order by `lib_titles`.`alpha` asc, `lib_titles`.`alpha` asc
 */
trait LibraryTableRowsTrait
{
    public static function getLibraryItems(
        int $libraryId,
        int $ensembleId = 0,
        string $searchValue = '',
        string $sortCol = 'lib_titles.title',
        bool $sortAsc = true,
        int $voicingFilterId = 0,
        string $typeFilterDescr = 'all',
    ): array
    {
        $searchFor = '%'.$searchValue.'%';

        $voicingOperand = ($voicingFilterId > 0)
            ? '='
            : '>';

        $typeFilters = self::calcTypeFilters($typeFilterDescr);


        return LibStack::query()
            ->join('lib_items', 'lib_stacks.lib_item_id', '=', 'lib_items.id')
            ->join('lib_titles', 'lib_items.lib_title_id', '=', 'lib_titles.id')
            ->leftJoin('artists AS composer', 'lib_items.composer_id', '=', 'composer.id')
            ->leftJoin('artists AS arranger', 'lib_items.arranger_id', '=', 'arranger.id')
            ->leftJoin('artists AS wam', 'lib_items.wam_id', '=', 'wam.id')
            ->leftJoin('artists AS words', 'lib_items.words_id', '=', 'words.id')
            ->leftJoin('artists AS music', 'lib_items.music_id', '=', 'music.id')
            ->leftJoin('artists AS choreographer', 'lib_items.choreographer_id', '=', 'choreographer.id')
            ->leftJoin('artists AS author', 'lib_items.author_id', '=', 'author.id')
            ->leftJoin('voicings', 'lib_items.voicing_id', '=', 'voicings.id')
            ->leftJoin('taggables', 'lib_items.id', '=', 'taggables.taggable_id')
            ->leftJoin('tags', 'taggables.tag_id', '=', 'tags.id')
            ->leftJoin('lib_medley_selections', 'lib_items.id', '=', 'lib_medley_selections.lib_item_id')
            ->leftJoin('lib_titles AS medley_titles', 'lib_medley_selections.lib_title_id', '=', 'medley_titles.id')
//            ->leftJoin('lib_digitals', 'lib_items.id', '=', 'lib_digitals.lib_item_id')
            ->where('lib_stacks.library_id', $libraryId)
            ->where(function ($query) use ($voicingOperand, $voicingFilterId) {
                $query->where('lib_items.voicing_id', $voicingOperand, $voicingFilterId);
//                    ->orWhereNull('lib_items.voicing_id');
            })
            ->where(function ($query) use ($typeFilters) {
                $query->whereIN('lib_items.item_type', $typeFilters);
//                    ->orWhereNull('lib_items.voicing_id');
            })
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
                    ->orWhere('choreographer.artist_name', 'LIKE', $searchFor)
                    ->orWhere('author.artist_name', 'LIKE', $searchFor)
                    ->orWhere('tags.name', 'LIKE', $searchFor)
                    ->orWhere('medley_titles.title', 'LIKE', $searchFor);
            })
            ->distinct()
            ->select('lib_stacks.id',
                'lib_stacks.count',
                'lib_items.id AS libItemId',
                'lib_titles.title', 'lib_titles.alpha', 'lib_items.item_type',
                'composer.alpha_name AS composerName',
                'arranger.alpha_name AS arrangerName',
                'wam.alpha_name AS wamName',
                'words.alpha_name AS wordsName',
                'music.alpha_name AS musicName',
                'choreographer.alpha_name AS choreographerName',
                'author.alpha_name AS authorName',
                'voicings.descr AS voicingDescr',
//                'lib_digitals.url AS libDigitalUrl',
//                'lib_digitals.label AS libDigitalUrlLabel',
                DB::raw('GROUP_CONCAT(DISTINCT medley_titles.title ORDER BY medley_titles.alpha SEPARATOR ", ") AS medleyTitles')
            )
            ->groupBy(
                'lib_stacks.id',
                'lib_titles.title',
                'lib_titles.alpha',
                'lib_items.item_type',
                'composer.alpha_name',
                'arranger.alpha_name',
                'wam.alpha_name',
                'words.alpha_name',
                'music.alpha_name',
                'choreographer.alpha_name',
                'author.alpha_name',
                'voicingDescr',
                'lib_items.id',
                'lib_stacks.count',
//                'lib_digitals.url',
//                'lib_digitals.label'
            )
            ->orderBy($sortCol, $sortAsc ? 'asc' : 'desc')
            ->orderBy('lib_titles.alpha', 'asc')
            ->get()
            ->toArray();
    }

    public static function getItemDocs(array $rows, int $libraryId, int $userId): array
    {
        $docs = [];
        foreach ($rows as $row) {
            $docs[$row['libItemId']] = LibItemDoc::query()
                ->where('lib_item_id', $row['libItemId'])
                ->where('library_id', $libraryId)
                ->where('user_id', $userId)
                ->select('url', 'label')
                ->get()
                ->toArray();
        }

        return $docs;
    }

    public static function getItemLocations(array $rows, int $libraryId): array
    {
        $locations = [];

        foreach ($rows as $row) {

            $libItemLocation = LibItemLocation::query()
                ->where('lib_item_id', $row['libItemId'])
                ->where('library_id', $libraryId)
                ->first();

            $fLocation = ($libItemLocation)
                ? $libItemLocation->formatLocation
                : $row['libItemId'];

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

    public static function getItemUrls(array $rows): array
    {
        $urls = [];
        foreach ($rows as $row) {
            $urls[$row['libItemId']] = LibDigital::query()
                ->where('lib_item_id', $row['libItemId'])
                ->select('url', 'label')
                ->get()
                ->toArray();
        }

        return $urls;
    }

    private static function calcTypeFilters(string $typeDescr): array
    {
        return match ($typeDescr) {
            'all' => ['octavo', 'medley', 'book', 'digital', 'cd', 'dvd', 'cassette', 'vinyl'],
            'paper' => ['octavo', 'medley', 'book'],
            'recordings' => ['digital', 'cd', 'dvd', 'cassette', 'vinyl'],
            $typeDescr => [$typeDescr],
        };
    }
}
