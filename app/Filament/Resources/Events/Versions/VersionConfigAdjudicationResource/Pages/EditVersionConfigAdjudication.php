<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigAdjudicationResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigAdjudicationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVersionConfigAdjudication extends EditRecord
{
    protected static string $resource = VersionConfigAdjudicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
