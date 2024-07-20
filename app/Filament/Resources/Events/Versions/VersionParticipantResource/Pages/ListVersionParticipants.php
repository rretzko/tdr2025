<?php

namespace App\Filament\Resources\Events\Versions\VersionParticipantResource\Pages;

use App\Filament\Resources\Events\Versions\VersionParticipantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVersionParticipants extends ListRecords
{
    protected static string $resource = VersionParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
