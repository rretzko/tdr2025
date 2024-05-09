<?php

namespace App\Filament\Resources\Schools\SchoolTeacherResource\Pages;

use App\Filament\Resources\Schools\SchoolTeacherResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSchoolTeacher extends EditRecord
{
    protected static string $resource = SchoolTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
