<?php

namespace App\Filament\Resources\EmergencyContactResource\Pages;

use App\Filament\Resources\EmergencyContactResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmergencyContacts extends ListRecords
{
    protected static string $resource = EmergencyContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
