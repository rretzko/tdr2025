<?php

namespace App\Filament\Resources\CountyResource\Pages;

use App\Filament\Resources\CountyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCounty extends CreateRecord
{
    protected static string $resource = CountyResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}