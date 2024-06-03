<?php

namespace App\Filament\Resources\Students\VoicePartResource\Pages;

use App\Filament\Resources\Students\VoicePartResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVoiceParts extends ListRecords
{
    protected static string $resource = VoicePartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
