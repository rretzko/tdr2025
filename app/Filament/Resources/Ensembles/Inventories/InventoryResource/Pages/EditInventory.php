<?php

namespace App\Filament\Resources\Ensembles\Inventories\InventoryResource\Pages;

use App\Filament\Resources\Ensembles\Inventories\InventoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInventory extends EditRecord
{
    protected static string $resource = InventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
