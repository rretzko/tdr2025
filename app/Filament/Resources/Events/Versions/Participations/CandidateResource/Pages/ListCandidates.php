<?php

namespace App\Filament\Resources\Events\Versions\Participations\CandidateResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\CandidateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCandidates extends ListRecords
{
    protected static string $resource = CandidateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
