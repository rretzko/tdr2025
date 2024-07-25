<?php

namespace App\Filament\Resources\Events\Versions\VersionScoringResource\Pages;

use App\Filament\Resources\Events\Versions\VersionScoringResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVersionScorings extends ListRecords
{
    protected static string $resource = VersionScoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
