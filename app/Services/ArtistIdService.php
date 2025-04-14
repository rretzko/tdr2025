<?php

namespace App\Services;

use App\Models\Libraries\Items\Components\Artist;

class ArtistIdService
{
    private int $id = 0;

    public function __construct(private readonly string $name)
    {
        $this->init();
    }

    private function init(): void
    {
        $service = new ArtistNameService($this->name);

        //early exit
        if (!$service->lastName) {
            return;
        }

        //search for an existing match by full_name and last_name
        $this->searchBy($service);

        //if no match is found, add the artist to the table
        $this->addArtist($service);

    }

    private function searchBy(ArtistNameService $service): void
    {
        $this->searchByFullName($service);

        if (!$this->id) {
            $this->searchByLastName($service);
        }
    }

    private function searchByFullName(ArtistNameService $service): void
    {
        $artist = Artist::where('artist_name', $service->artistName)->first();

        $this->id = $artist ? $artist->id : 0;
    }

    private function searchByLastName(ArtistNameService $service): void
    {
        if (!$this->id) {
            $artists = Artist::where('last_name', $service->lastName)->get();

            //if multiple artists are found with the same last name, default to the first one found
            $this->id = $artists->count() ? $artists[0]->id : 0;
        }
    }

    private function addArtist(ArtistNameService $service): void
    {
        //early exit
        if ($this->id) {
            return;
        }

        $artist = Artist::create([
            'artist_name' => $service->artistName,
            'first_name' => $service->firstName,
            'last_name' => $service->lastName,
            'middle_name' => $service->middleName,
            'alpha_name' => $service->alphaName,
            'created_by' => auth()->id(),
        ]);

        $this->id = $artist->id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
