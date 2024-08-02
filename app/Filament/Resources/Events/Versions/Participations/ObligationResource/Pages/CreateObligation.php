<?php

namespace App\Filament\Resources\Events\Versions\Participations\ObligationResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\ObligationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateObligation extends CreateRecord
{
    protected static string $resource = ObligationResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
