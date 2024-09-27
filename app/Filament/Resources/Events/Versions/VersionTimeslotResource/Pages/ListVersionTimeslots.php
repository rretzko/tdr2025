<?php

namespace App\Filament\Resources\Events\Versions\VersionTimeslotResource\Pages;

use App\Filament\Resources\Events\Versions\VersionTimeslotResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVersionTimeslots extends ListRecords
{
    protected static string $resource = VersionTimeslotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
