<?php

namespace App\Filament\Resources\UserFilterResource\Pages;

use App\Filament\Resources\UserFilterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserFilters extends ListRecords
{
    protected static string $resource = UserFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
