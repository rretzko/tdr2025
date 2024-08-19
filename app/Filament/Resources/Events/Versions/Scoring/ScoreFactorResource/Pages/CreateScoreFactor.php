<?php

namespace App\Filament\Resources\Events\Versions\Scoring\ScoreFactorResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\ScoreFactorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateScoreFactor extends CreateRecord
{
    protected static string $resource = ScoreFactorResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
