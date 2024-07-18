<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigRegistrantResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigRegistrantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVersionConfigRegistrants extends ListRecords
{
    protected static string $resource = VersionConfigRegistrantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
