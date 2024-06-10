<?php

namespace App\Filament\Resources\Ensembles\EnsembleResource\Pages;

use App\Filament\Resources\Ensembles\EnsembleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEnsemble extends CreateRecord
{
    protected static string $resource = EnsembleResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
