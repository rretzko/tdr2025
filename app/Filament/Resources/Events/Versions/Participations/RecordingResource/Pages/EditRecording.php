<?php

namespace App\Filament\Resources\Events\Versions\Participations\RecordingResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\RecordingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRecording extends EditRecord
{
    protected static string $resource = RecordingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
