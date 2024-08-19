<?php

namespace App\Filament\Resources\Events\Versions\Scoring\RoomResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\RoomResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRoom extends CreateRecord
{
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
