<?php

namespace App\Filament\Resources\Events\Versions\Scoring\RoomResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\RoomResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRooms extends ListRecords
{
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
