<?php

namespace App\Filament\Resources\Events\EventManagementResource\Pages;

use App\Filament\Resources\Events\EventManagementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEventManagements extends ListRecords
{
    protected static string $resource = EventManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
