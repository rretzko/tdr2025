<?php

namespace App\Filament\Resources\Schools\SchoolTeacherResource\Pages;

use App\Filament\Resources\Schools\SchoolTeacherResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSchoolTeacher extends CreateRecord
{
    protected static string $resource = SchoolTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
