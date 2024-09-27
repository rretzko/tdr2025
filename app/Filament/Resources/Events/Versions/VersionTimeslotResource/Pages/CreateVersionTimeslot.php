<?php

namespace App\Filament\Resources\Events\Versions\VersionTimeslotResource\Pages;

use App\Filament\Resources\Events\Versions\VersionTimeslotResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVersionTimeslot extends CreateRecord
{
    protected static string $resource = VersionTimeslotResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
