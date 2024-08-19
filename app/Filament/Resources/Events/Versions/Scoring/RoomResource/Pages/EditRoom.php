<?php

namespace App\Filament\Resources\Events\Versions\Scoring\RoomResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\RoomResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoom extends EditRecord
{
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
