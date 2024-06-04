<?php

namespace App\Filament\Resources\StudentTeacherResource\Pages;

use App\Filament\Resources\StudentTeacherResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentTeacher extends CreateRecord
{
    protected static string $resource = StudentTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
