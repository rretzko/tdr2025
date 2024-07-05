<?php

namespace App\Filament\Resources\Schools\Teachers\TeacherSubjectResource\Pages;

use App\Filament\Resources\Schools\Teachers\TeacherSubjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacherSubject extends CreateRecord
{
    protected static string $resource = TeacherSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
