<?php

namespace App\Filament\Resources\Events\Versions\Scoring\ScoreFactorResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\ScoreFactorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScoreFactors extends ListRecords
{
    protected static string $resource = ScoreFactorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
