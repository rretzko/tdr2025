<?php

namespace App\Filament\Resources\Students\VoicePartResource\Pages;

use App\Filament\Resources\Students\VoicePartResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVoicePart extends EditRecord
{
    protected static string $resource = VoicePartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
