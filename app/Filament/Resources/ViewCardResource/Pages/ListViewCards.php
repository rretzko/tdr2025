<?php

namespace App\Filament\Resources\ViewCardResource\Pages;

use App\Filament\Resources\ViewCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListViewCards extends ListRecords
{
    protected static string $resource = ViewCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
