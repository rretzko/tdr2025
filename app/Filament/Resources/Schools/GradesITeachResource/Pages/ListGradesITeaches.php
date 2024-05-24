<?php

namespace App\Filament\Resources\Schools\GradesITeachResource\Pages;

use App\Filament\Resources\Schools\GradesITeachResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGradesITeaches extends ListRecords
{
    protected static string $resource = GradesITeachResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
