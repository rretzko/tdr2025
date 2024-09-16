<?php

namespace App\Filament\Resources\Events\Versions\Scoring\RoomScoreCategoriesResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\RoomScoreCategoriesResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRoomScoreCategories extends CreateRecord
{
    protected static string $resource = RoomScoreCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
