<?php

namespace App\Filament\Resources\Ensembles\EnsembleResource\Pages;

use App\Filament\Resources\Ensembles\EnsembleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEnsemble extends EditRecord
{
    protected static string $resource = EnsembleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
