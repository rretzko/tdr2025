<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigRegistrantResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigRegistrantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVersionConfigRegistrant extends EditRecord
{
    protected static string $resource = VersionConfigRegistrantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
