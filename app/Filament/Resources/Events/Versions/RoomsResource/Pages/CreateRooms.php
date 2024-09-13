<?php

namespace App\Filament\Resources\Events\Versions\RoomsResource\Pages;

use App\Filament\Resources\Events\Versions\RoomsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRooms extends CreateRecord
{
    protected static string $resource = RoomsResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
