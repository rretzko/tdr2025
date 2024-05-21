<?php

namespace App\Filament\Resources\PageInstructionsResource\Pages;

use App\Filament\Resources\PageInstructionsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPageInstructions extends EditRecord
{
    protected static string $resource = PageInstructionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
