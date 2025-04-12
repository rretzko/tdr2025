<?php

namespace App\Services;

use App\Models\Libraries\Items\Components\Artist;

class ArtistNameService
{
    public function __construct(private Artist $artist, private readonly string $artistName)
    {

    }
}
