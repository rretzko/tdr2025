<?php

namespace App\Filament\Resources\Events\Versions\Scoring\ScoreCategoryResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\ScoreCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditScoreCategory extends EditRecord
{
    protected static string $resource = ScoreCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
