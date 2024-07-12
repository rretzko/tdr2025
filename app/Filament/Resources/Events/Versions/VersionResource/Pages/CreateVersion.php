<?php

namespace App\Filament\Resources\Events\Versions\VersionResource\Pages;

use App\Filament\Resources\Events\Versions\VersionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVersion extends CreateRecord
{
    protected static string $resource = VersionResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
