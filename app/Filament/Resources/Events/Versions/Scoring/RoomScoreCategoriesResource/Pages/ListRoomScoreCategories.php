<?php

namespace App\Filament\Resources\Events\Versions\Scoring\RoomScoreCategoriesResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\RoomScoreCategoriesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoomScoreCategories extends ListRecords
{
    protected static string $resource = RoomScoreCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
