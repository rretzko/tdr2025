<?php

namespace App\Filament\Resources\Ensembles\AssetEnsembleResource\Pages;

use App\Filament\Resources\Ensembles\AssetEnsembleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssetEnsemble extends CreateRecord
{
    protected static string $resource = AssetEnsembleResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
