<?php

namespace App\Filament\Resources\Events\EventManagementResource\Pages;

use App\Filament\Resources\Events\EventManagementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEventManagement extends CreateRecord
{
    protected static string $resource = EventManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
