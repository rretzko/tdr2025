<?php

namespace App\Filament\Resources\Events\Versions\Scoring\ScoreCategoryResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\ScoreCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateScoreCategory extends CreateRecord
{
    protected static string $resource = ScoreCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
