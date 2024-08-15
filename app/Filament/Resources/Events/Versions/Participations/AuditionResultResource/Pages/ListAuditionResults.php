<?php

namespace App\Filament\Resources\Events\Versions\Participations\AuditionResultResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\AuditionResultResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuditionResults extends ListRecords
{
    protected static string $resource = AuditionResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
