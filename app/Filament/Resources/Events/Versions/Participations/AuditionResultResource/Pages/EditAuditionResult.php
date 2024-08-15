<?php

namespace App\Filament\Resources\Events\Versions\Participations\AuditionResultResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\AuditionResultResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuditionResult extends EditRecord
{
    protected static string $resource = AuditionResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
