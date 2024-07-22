<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigPitchFileResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigPitchFileResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditVersionConfigPitchFile extends EditRecord
{
    protected static string $resource = VersionConfigPitchFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
