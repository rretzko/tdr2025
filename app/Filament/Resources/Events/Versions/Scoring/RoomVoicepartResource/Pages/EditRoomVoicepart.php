<?php

namespace App\Filament\Resources\Events\Versions\Scoring\RoomVoicepartResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\RoomVoicepartResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoomVoicepart extends EditRecord
{
    protected static string $resource = RoomVoicepartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
