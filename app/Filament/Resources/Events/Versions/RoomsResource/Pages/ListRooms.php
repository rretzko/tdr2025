<?php

namespace App\Filament\Resources\Events\Versions\RoomsResource\Pages;

use App\Filament\Resources\Events\Versions\RoomsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRooms extends ListRecords
{
    protected static string $resource = RoomsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
