<?php

namespace App\Filament\Resources\PronounResource\Pages;

use App\Filament\Resources\PronounResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePronoun extends CreateRecord
{
    protected static string $resource = PronounResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
