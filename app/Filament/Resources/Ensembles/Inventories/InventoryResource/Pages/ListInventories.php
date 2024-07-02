<?php

namespace App\Filament\Resources\Ensembles\Inventories\InventoryResource\Pages;

use App\Filament\Resources\Ensembles\Inventories\InventoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventories extends ListRecords
{
    protected static string $resource = InventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
