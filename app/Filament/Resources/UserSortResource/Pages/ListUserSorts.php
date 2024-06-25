<?php

namespace App\Filament\Resources\UserSortResource\Pages;

use App\Filament\Resources\UserSortResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserSorts extends ListRecords
{
    protected static string $resource = UserSortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
