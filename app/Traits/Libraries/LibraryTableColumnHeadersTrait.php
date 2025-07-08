<?php

namespace App\Traits\Libraries;

use Illuminate\Http\Request;

trait LibraryTableColumnHeadersTrait
{
    public static function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'type', 'sortBy' => 'type'],
            ['label' => 'title', 'sortBy' => 'title'],
            ['label' => 'artists', 'sortBy' => null],
            ['label' => 'voicing', 'sortBy' => 'voicing'],
            ['label' => 'tags', 'sortBy' => null],
            ['label' => 'perf', 'sortBy' => null],
            ['label' => 'pull', 'sortBy' => null],
        ];
    }
}
