<?php

namespace App\Filament\Resources\GeostateResource\Pages;

use App\Filament\Resources\GeostateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGeostate extends EditRecord
{
    protected static string $resource = GeostateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
