<?php

namespace App\Filament\Resources\EmergencyContactTypesResource\Pages;

use App\Filament\Resources\EmergencyContactTypesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmergencyContactTypes extends EditRecord
{
    protected static string $resource = EmergencyContactTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
