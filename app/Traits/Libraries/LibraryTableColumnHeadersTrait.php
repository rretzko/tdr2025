<?php

namespace App\Traits\Libraries;

use Illuminate\Http\Request;

trait LibraryTableColumnHeadersTrait
{
    public static function getColumnHeaders(): array
    {
        $colHeaders = [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'type/location', 'sortBy' => 'type'],
            ['label' => 'title', 'sortBy' => 'title'],
            ['label' => 'count', 'sortBy' => null],
            ['label' => 'artists', 'sortBy' => null],
            ['label' => 'voicing', 'sortBy' => 'voicing'],
            ['label' => 'tags', 'sortBy' => null],
            ['label' => 'web', 'sortBy' => null],
        ];

        if (auth()->user()->isTeacher()) {
            $colHeaders[] = ['label' => 'perf', 'sortBy' => null];
        }

        $colHeaders[] = ['label' => 'pull', 'sortBy' => null];

        return $colHeaders;
    }
}
