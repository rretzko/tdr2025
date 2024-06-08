<?php

namespace App\Filament\Resources\EmergencyContactTypesResource\Pages;

use App\Filament\Resources\EmergencyContactTypesResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmergencyContactTypes extends CreateRecord
{
    protected static string $resource = EmergencyContactTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
