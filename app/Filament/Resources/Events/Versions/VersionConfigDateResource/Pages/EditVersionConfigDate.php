<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigDateResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigDateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVersionConfigDate extends EditRecord
{
    protected static string $resource = VersionConfigDateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
