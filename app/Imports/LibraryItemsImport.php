<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LibraryItemsImport implements ToModel, WithHeadingRow
{
    /**
     * array:15 [
     * 0 => type,
     * 1 => title,
     * 2 => voicing,
     * 3 => composer,
     * 4 => arranger,
     * 5 => words-and-music,
     * 6 => words,
     * 7 => music,
     * 8 => choreographer,
     * 9 => tags,
     * 10 => copies,
     * 11 => price,
     * 12 => location1,
     * 13 => location2,
     * 14 => location3,
     * ]
     * @param  array  $row
     * @return void
     */
    public function model(array $row)
    {
        static $counter = 0;
        if ($row[0] !== 'type') { //skip header
            //1. Create a new item
            //  1.a Use an existing item if available
            //2. Add item to libStacks
        }
    }
}
