<?php

namespace App\Filament\Resources\ViewPageResource\Pages;

use App\Filament\Resources\ViewPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditViewPage extends EditRecord
{
    protected static string $resource = ViewPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
