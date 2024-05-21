<?php

namespace App\Filament\Resources\PageInstructionsResource\Pages;

use App\Filament\Resources\PageInstructionsResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePageInstructions extends CreateRecord
{
    protected static string $resource = PageInstructionsResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
