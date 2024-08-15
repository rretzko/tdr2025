<?php

namespace App\Filament\Resources\Events\Versions\Scoring\ScoreCategoryResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\ScoreCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScoreCategories extends ListRecords
{
    protected static string $resource = ScoreCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
