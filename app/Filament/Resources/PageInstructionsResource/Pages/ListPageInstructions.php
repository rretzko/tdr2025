<?php

namespace App\Filament\Resources\PageInstructionsResource\Pages;

use App\Filament\Resources\PageInstructionsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPageInstructions extends ListRecords
{
    protected static string $resource = PageInstructionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
