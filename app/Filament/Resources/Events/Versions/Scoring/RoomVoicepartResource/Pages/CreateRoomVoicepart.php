<?php

namespace App\Filament\Resources\Events\Versions\Scoring\RoomVoicepartResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\RoomVoicepartResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRoomVoicepart extends CreateRecord
{
    protected static string $resource = RoomVoicepartResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
