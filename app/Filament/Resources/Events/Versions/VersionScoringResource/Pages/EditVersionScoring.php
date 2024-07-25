<?php

namespace App\Filament\Resources\Events\Versions\VersionScoringResource\Pages;

use App\Filament\Resources\Events\Versions\VersionScoringResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVersionScoring extends EditRecord
{
    protected static string $resource = VersionScoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
