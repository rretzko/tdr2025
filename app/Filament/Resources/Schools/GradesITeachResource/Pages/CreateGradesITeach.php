<?php

namespace App\Filament\Resources\Schools\GradesITeachResource\Pages;

use App\Filament\Resources\Schools\GradesITeachResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGradesITeach extends CreateRecord
{
    protected static string $resource = GradesITeachResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
