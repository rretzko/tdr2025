<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigDateResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigDateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVersionConfigDates extends ListRecords
{
    protected static string $resource = VersionConfigDateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
