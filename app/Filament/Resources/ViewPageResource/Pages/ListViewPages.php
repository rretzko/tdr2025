<?php

namespace App\Filament\Resources\ViewPageResource\Pages;

use App\Filament\Resources\ViewPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListViewPages extends ListRecords
{
    protected static string $resource = ViewPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
