<?php

namespace App\Filament\Resources\Events\Versions\Scoring\ScoreResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\ScoreResource;
use Filament\Resources\Pages\CreateRecord;

class CreateScore extends CreateRecord
{
    protected static string $resource = ScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
