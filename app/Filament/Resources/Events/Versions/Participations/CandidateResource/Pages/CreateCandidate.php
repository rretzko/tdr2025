<?php

namespace App\Filament\Resources\Events\Versions\Participations\CandidateResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\CandidateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCandidate extends CreateRecord
{
    protected static string $resource = CandidateResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
