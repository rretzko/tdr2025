<?php

namespace App\Filament\Resources\Students\VoicePartResource\Pages;

use App\Filament\Resources\Students\VoicePartResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVoicePart extends CreateRecord
{
    protected static string $resource = VoicePartResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
