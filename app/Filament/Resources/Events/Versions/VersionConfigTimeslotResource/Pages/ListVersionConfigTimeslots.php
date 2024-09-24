<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigTimeslotResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigTimeslotResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVersionConfigTimeslots extends ListRecords
{
    protected static string $resource = VersionConfigTimeslotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
