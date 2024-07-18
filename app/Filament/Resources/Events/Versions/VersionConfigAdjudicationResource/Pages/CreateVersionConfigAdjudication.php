<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigAdjudicationResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigAdjudicationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVersionConfigAdjudication extends CreateRecord
{
    protected static string $resource = VersionConfigAdjudicationResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
