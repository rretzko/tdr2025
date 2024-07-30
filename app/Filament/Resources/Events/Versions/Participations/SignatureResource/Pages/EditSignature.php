<?php

namespace App\Filament\Resources\Events\Versions\Participations\SignatureResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\SignatureResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSignature extends EditRecord
{
    protected static string $resource = SignatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
