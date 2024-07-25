<?php

namespace App\Filament\Resources\Events\Versions\VersionScoringResource\Pages;

use App\Filament\Resources\Events\Versions\VersionScoringResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVersionScoring extends CreateRecord
{
    protected static string $resource = VersionScoringResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
