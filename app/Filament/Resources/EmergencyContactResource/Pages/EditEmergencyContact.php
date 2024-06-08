<?php

namespace App\Filament\Resources\EmergencyContactResource\Pages;

use App\Filament\Resources\EmergencyContactResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmergencyContact extends EditRecord
{
    protected static string $resource = EmergencyContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
