<?php

namespace App\Filament\Resources\Ensembles\AssetEnsembleResource\Pages;

use App\Filament\Resources\Ensembles\AssetEnsembleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssetEnsembles extends ListRecords
{
    protected static string $resource = AssetEnsembleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
