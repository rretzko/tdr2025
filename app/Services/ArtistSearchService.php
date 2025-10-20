<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ArtistSearchService
{
    private static bool|array $results = false;

    public static function getResults(string $searchFor, string $artistType): array|bool
    {
        self::init($searchFor, $artistType);

        return self::$results;
    }

    /**
     * artists are return ordered by last_name and then first name
     * This ordering returns all multi-artist artists( ex. John Lennon and Paul McCartney)
     * first (first_name on these artists is blank), in last_name order
     * and then all single artists (ex. Ringo Starr) in first_name, last_name order
    */
    private static function init(string $searchFor, string $artistType): void
    {
        $search = '%'.$searchFor.'%';

        $artists = DB::table('artists')
            ->where('artist_name', 'LIKE', $search)
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->get();

        foreach ($artists as $artist) {

            self::$results[] = [
                'alpha' => $artist->alpha_name,
                'name' => $artist->artist_name,
                'type' => $artistType,
                'id' => $artist->id,
            ];
        }
    }
}
