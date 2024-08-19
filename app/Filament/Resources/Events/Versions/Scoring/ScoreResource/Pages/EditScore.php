<?php

namespace App\Filament\Resources\Events\Versions\Scoring\ScoreResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\ScoreResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditScore extends EditRecord
{
    protected static string $resource = ScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
