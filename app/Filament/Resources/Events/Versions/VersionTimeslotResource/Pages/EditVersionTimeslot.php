<?php

namespace App\Filament\Resources\Events\Versions\VersionTimeslotResource\Pages;

use App\Filament\Resources\Events\Versions\VersionTimeslotResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVersionTimeslot extends EditRecord
{
    protected static string $resource = VersionTimeslotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
