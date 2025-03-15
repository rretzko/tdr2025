<?php

namespace App\Services;

class ArtistSearchService
{
    private static bool|array $results = false;

    public static function getResults(string $searchFor, string $artistType): array
    {
        self::init($searchFor, $artistType);

        return self::$results;
    }

    private static function init(string $searchFor, string $artistType): void
    {
        self::$results = [
            [
                'name' => 'Artist 1',
                'type' => $artistType,
                'id' => 1,
            ],
            [
                'name' => 'Artist 2',
                'type' => $artistType,
                'id' => 2,
            ],
            [
                'name' => 'Artist 3',
                'type' => $artistType,
                'id' => 3,
            ],

        ];
    }
}
