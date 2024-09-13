<?php

namespace App\Filament\Resources\Events\Versions\RoomsResource\Pages;

use App\Filament\Resources\Events\Versions\RoomsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRooms extends EditRecord
{
    protected static string $resource = RoomsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
