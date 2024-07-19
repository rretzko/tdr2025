<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigDateResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigDateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVersionConfigDate extends CreateRecord
{
    protected static string $resource = VersionConfigDateResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
