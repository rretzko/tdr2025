<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigPitchFileResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigPitchFileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVersionConfigPitchFile extends CreateRecord
{
    protected static string $resource = VersionConfigPitchFileResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
