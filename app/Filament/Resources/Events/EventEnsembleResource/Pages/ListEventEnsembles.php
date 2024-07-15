<?php

namespace App\Filament\Resources\Events\EventEnsembleResource\Pages;

use App\Filament\Resources\Events\EventEnsembleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEventEnsembles extends ListRecords
{
    protected static string $resource = EventEnsembleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
