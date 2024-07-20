<?php

namespace App\Filament\Resources\Events\Versions\VersionParticipantResource\Pages;

use App\Filament\Resources\Events\Versions\VersionParticipantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVersionParticipant extends CreateRecord
{
    protected static string $resource = VersionParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
