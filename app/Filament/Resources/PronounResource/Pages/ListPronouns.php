<?php

namespace App\Filament\Resources\PronounResource\Pages;

use App\Filament\Resources\PronounResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPronouns extends ListRecords
{
    protected static string $resource = PronounResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
