<?php

namespace App\Filament\Resources\Events\Versions\Scoring\RoomScoreCategoriesResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\RoomScoreCategoriesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoomScoreCategories extends EditRecord
{
    protected static string $resource = RoomScoreCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
