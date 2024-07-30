<?php

namespace App\Filament\Resources\Events\Versions\Participations\SignatureResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\SignatureResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSignature extends CreateRecord
{
    protected static string $resource = SignatureResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
