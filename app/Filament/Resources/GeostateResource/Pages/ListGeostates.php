<?php

namespace App\Filament\Resources\GeostateResource\Pages;

use App\Filament\Resources\GeostateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGeostates extends ListRecords
{
    protected static string $resource = GeostateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
