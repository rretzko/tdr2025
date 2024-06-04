<?php

namespace App\Filament\Resources\StudentTeacherResource\Pages;

use App\Filament\Resources\StudentTeacherResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentTeacher extends EditRecord
{
    protected static string $resource = StudentTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
