<?php

namespace App\Filament\Resources\Libraries\LibraryResource\Pages;

use App\Filament\Resources\Libraries\LibraryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLibrary extends CreateRecord
{
    protected static string $resource = LibraryResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
