<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigPitchFileResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigPitchFileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVersionConfigPitchFiles extends ListRecords
{
    protected static string $resource = VersionConfigPitchFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
