<?php

namespace App\Filament\Resources\Schools\GradesITeachResource\Pages;

use App\Filament\Resources\Schools\GradesITeachResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGradesITeach extends EditRecord
{
    protected static string $resource = GradesITeachResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
