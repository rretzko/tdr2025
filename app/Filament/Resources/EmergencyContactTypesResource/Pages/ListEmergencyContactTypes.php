<?php

namespace App\Filament\Resources\EmergencyContactTypesResource\Pages;

use App\Filament\Resources\EmergencyContactTypesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmergencyContactTypes extends ListRecords
{
    protected static string $resource = EmergencyContactTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
