<?php

namespace App\Filament\Resources\Schools\SchoolGradeResource\Pages;

use App\Filament\Resources\Schools\SchoolGradeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSchoolGrade extends EditRecord
{
    protected static string $resource = SchoolGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
