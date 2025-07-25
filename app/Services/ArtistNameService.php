<?php

namespace App\Services;

use App\Models\Libraries\Items\Components\Artist;
use Illuminate\Support\Str;

class ArtistNameService
{
    public string $alphaName = '';
    public string $artistName = '';
    public string $firstName = '';
    private int $id = 0;
    public string $lastName = '';
    public string $middleName = '';

    public function __construct(string $artistName)
    {
        if (strlen($artistName)) {
            $this->parseName($artistName);
            $this->artistName = $this->setArtistName();
            $this->alphaName = $this->setAlphaName();
        }
    }

    private function parseName(string $artistName): void
    {
        if ($this->isMultiArtist($artistName)) {

            $this->parseMultiArtistName($artistName);

        } else {

            $parts = explode(' ', $artistName);
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

    }

    private function isMultiArtist(string $artistName): bool
    {
        $lcArtistName = strtolower($artistName);
        $delimiters = [' and ', ' & ', '/', ';'];
        return Str::contains($lcArtistName, $delimiters);
    }

    /**
     * ex. Jerry Leiber, MIke Stoller and Ben E. King
     * @param  string  $artistName
     * @return void
     */
    private function parseMultiArtistName(string $artistName): void
    {
        $this->lastName = trim($artistName);
        $this->firstName = '';
        $this->middleName = '';
    }

    private function setAlphaName(): string
    {
        if (!$this->firstName) {
            return $this->lastName;
        }

        return trim($this->lastName.', '.$this->firstName.' '.$this->middleName);

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
}
