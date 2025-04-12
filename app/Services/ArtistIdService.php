<?php

namespace App\Services;

use App\Models\Libraries\Items\Components\Artist;

class ArtistIdService
{
    private string $alphaName = '';
    private string $artistName = '';
    private string $firstName = '';
    private int $id = 0;
    private string $lastName = '';
    private string $middleName = '';

    public function __construct(private readonly string $name)
    {
        $this->init();
    }

    private function init(): void
    {
        $this->parseName();
        $this->artistName = $this->setArtistName();
        $this->alphaName = $this->setAlphaName();

        //early exit
        if (!$this->lastName) {
            return;
        }

        //search for an existing match by full_name and last_name
        $this->searchBy();

        //if no match is found, add the artist to the table
        $this->addArtist();

    }

    private function parseName(): void
    {
        $parts = explode(' ', $this->name);
        $partCount = count($parts);

        if (count($parts) === 1) {
            $this->lastName = trim($parts[0]);
        } elseif (count($parts) === 2) {
            $this->firstName = trim($parts[0]);
            $this->lastName = trim($parts[$partCount - 1]);
        } else {
            $this->firstName = trim($parts[0]);
            $this->lastName = trim($parts[$partCount - 1]);
            $this->middleName = implode(' ', array_slice($parts, 1, ($partCount - 2)));
        }
    }

    private function setArtistName(): string
    {
        //early exit if only last name
        if (!$this->firstName) {
            return $this->lastName;
        }

        $str = $this->firstName;

        if ($this->middleName) {
            $str .= ' '.$this->middleName;
        }

        $str .= ' '.$this->lastName;

        return $str;
    }

    private function setAlphaName(): string
    {
        if (!$this->firstName) {
            return $this->lastName;
        }

        return trim($this->lastName.', '.$this->firstName.' '.$this->middleName);

    }

    private function searchBy(): void
    {
        $this->searchByFullName();

        if (!$this->id) {
            $this->searchByLastName();
        }
    }

    private function searchByFullName(): void
    {
        $artist = Artist::where('artist_name', $this->artistName)->first();

        $this->id = $artist ? $artist->id : 0;
    }

    private function searchByLastName(): void
    {
        if (!$this->id) {
            $artists = Artist::where('last_name', $this->lastName)->get();

            //if multiple artists are found with the same last name, default to the first one found
            $this->id = $artists->count() ? $artists[0]->id : 0;
        }
    }

    private function addArtist(): void
    {
        //early exit
        if ($this->id) {
            return;
        }

        $artist = Artist::create([
            'artist_name' => $this->artistName,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'middle_name' => $this->middleName,
            'alpha_name' => $this->alphaName,
            'created_by' => auth()->id(),
        ]);

        $this->id = $artist->id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
