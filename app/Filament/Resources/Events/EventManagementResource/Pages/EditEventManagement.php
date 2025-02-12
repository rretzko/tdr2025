<?php

namespace App\Filament\Resources\Events\EventManagementResource\Pages;

use App\Filament\Resources\Events\EventManagementResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditEventManagement extends EditRecord
{
    protected static string $resource = EventManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
