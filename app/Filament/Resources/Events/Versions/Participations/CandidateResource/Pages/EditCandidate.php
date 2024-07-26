<?php

namespace App\Filament\Resources\Events\Versions\Participations\CandidateResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\CandidateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCandidate extends EditRecord
{
    protected static string $resource = CandidateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
