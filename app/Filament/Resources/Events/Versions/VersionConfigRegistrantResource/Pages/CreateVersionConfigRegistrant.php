<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigRegistrantResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigRegistrantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVersionConfigRegistrant extends CreateRecord
{
    protected static string $resource = VersionConfigRegistrantResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
