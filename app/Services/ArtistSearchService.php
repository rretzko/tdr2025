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

    private static function init(string $searchFor, string $artistType): void
    {
        $search = '%'.$searchFor.'%';

        $artists = DB::table('artists')
            ->where('artist_name', 'LIKE', $search)
            ->orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc')
            ->get();

        foreach ($artists as $artist) {

            self::$results[] = [
                'name' => $artist->alpha_name,
                'type' => $artistType,
                'id' => $artist->id,
            ];
        }
    }
}
