<?php

namespace App\Filament\Resources\Events\Versions\Participations\RecordingResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\RecordingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRecordings extends ListRecords
{
    protected static string $resource = RecordingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
