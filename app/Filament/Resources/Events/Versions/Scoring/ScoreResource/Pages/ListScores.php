<?php

namespace App\Filament\Resources\Events\Versions\Scoring\ScoreResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\ScoreResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScores extends ListRecords
{
    protected static string $resource = ScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
