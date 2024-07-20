<?php

namespace App\Filament\Resources\Events\Versions\VersionParticipantResource\Pages;

use App\Filament\Resources\Events\Versions\VersionParticipantResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditVersionParticipant extends EditRecord
{
    protected static string $resource = VersionParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
