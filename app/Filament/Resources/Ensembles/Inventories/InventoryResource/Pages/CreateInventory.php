<?php

namespace App\Filament\Resources\Ensembles\Inventories\InventoryResource\Pages;

use App\Filament\Resources\Ensembles\Inventories\InventoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInventory extends CreateRecord
{
    protected static string $resource = InventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
