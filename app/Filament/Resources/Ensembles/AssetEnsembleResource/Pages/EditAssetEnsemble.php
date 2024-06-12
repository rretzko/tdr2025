<?php

namespace App\Filament\Resources\Ensembles\AssetEnsembleResource\Pages;

use App\Filament\Resources\Ensembles\AssetEnsembleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssetEnsemble extends EditRecord
{
    protected static string $resource = AssetEnsembleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
