<?php

namespace App\Filament\Resources\Events\EventEnsembleResource\Pages;

use App\Filament\Resources\Events\EventEnsembleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditEventEnsemble extends EditRecord
{
    protected static string $resource = EventEnsembleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
