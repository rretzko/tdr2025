<?php

namespace App\Filament\Resources\Ensembles\AssetResource\Pages;

use App\Filament\Resources\Ensembles\AssetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAsset extends CreateRecord
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
