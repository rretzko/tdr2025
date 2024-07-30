<?php

namespace App\Filament\Resources\Events\Versions\Participations\RecordingResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\RecordingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRecording extends CreateRecord
{
    protected static string $resource = RecordingResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
