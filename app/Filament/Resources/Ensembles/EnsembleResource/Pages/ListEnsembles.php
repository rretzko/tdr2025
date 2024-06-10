<?php

namespace App\Filament\Resources\Ensembles\EnsembleResource\Pages;

use App\Filament\Resources\Ensembles\EnsembleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEnsembles extends ListRecords
{
    protected static string $resource = EnsembleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
