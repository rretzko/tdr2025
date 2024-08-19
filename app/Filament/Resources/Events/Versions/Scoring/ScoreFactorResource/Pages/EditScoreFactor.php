<?php

namespace App\Filament\Resources\Events\Versions\Scoring\ScoreFactorResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\ScoreFactorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditScoreFactor extends EditRecord
{
    protected static string $resource = ScoreFactorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
