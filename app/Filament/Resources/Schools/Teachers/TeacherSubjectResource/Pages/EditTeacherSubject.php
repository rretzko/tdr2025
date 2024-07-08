<?php

namespace App\Filament\Resources\Schools\Teachers\TeacherSubjectResource\Pages;

use App\Filament\Resources\Schools\Teachers\TeacherSubjectResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTeacherSubject extends EditRecord
{
    protected static string $resource = TeacherSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
