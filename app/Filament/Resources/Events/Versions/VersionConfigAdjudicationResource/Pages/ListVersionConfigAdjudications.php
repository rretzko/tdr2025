<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigAdjudicationResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigAdjudicationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVersionConfigAdjudications extends ListRecords
{
    protected static string $resource = VersionConfigAdjudicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
