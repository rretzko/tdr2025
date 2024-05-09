<?php

namespace App\Filament\Resources\GeostateResource\Pages;

use App\Filament\Resources\GeostateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGeostate extends CreateRecord
{
    protected static string $resource = GeostateResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
