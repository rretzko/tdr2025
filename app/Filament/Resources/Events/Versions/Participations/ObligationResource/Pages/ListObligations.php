<?php

namespace App\Filament\Resources\Events\Versions\Participations\ObligationResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\ObligationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListObligations extends ListRecords
{
    protected static string $resource = ObligationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
