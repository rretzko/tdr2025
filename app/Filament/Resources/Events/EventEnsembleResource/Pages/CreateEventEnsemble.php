<?php

namespace App\Filament\Resources\Events\EventEnsembleResource\Pages;

use App\Filament\Resources\Events\EventEnsembleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEventEnsemble extends CreateRecord
{
    protected static string $resource = EventEnsembleResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
