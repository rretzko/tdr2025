<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigTimeslotResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigTimeslotResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVersionConfigTimeslot extends CreateRecord
{
    protected static string $resource = VersionConfigTimeslotResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
