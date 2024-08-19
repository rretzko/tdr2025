<?php

namespace App\Filament\Resources\Events\Versions\Scoring\RoomVoicepartResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\RoomVoicepartResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoomVoiceparts extends ListRecords
{
    protected static string $resource = RoomVoicepartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
