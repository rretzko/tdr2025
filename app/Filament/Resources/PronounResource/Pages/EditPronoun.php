<?php

namespace App\Filament\Resources\PronounResource\Pages;

use App\Filament\Resources\PronounResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPronoun extends EditRecord
{
    protected static string $resource = PronounResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
