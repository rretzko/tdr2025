<?php

namespace App\Filament\Resources\Events\Versions\Participations\AuditionResultResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\AuditionResultResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAuditionResult extends CreateRecord
{
    protected static string $resource = AuditionResultResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
