<?php

namespace App\Filament\Resources\Events\Versions\Participations\SignatureResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\SignatureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSignatures extends ListRecords
{
    protected static string $resource = SignatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
