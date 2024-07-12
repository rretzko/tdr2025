<?php

namespace App\Filament\Resources\Events\Versions\VersionResource\Pages;

use App\Filament\Resources\Events\Versions\VersionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVersions extends ListRecords
{
    protected static string $resource = VersionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
