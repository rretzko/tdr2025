<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigTimeslotResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigTimeslotResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVersionConfigTimeslot extends EditRecord
{
    protected static string $resource = VersionConfigTimeslotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
