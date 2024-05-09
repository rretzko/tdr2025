<?php

namespace App\Filament\Resources\Schools\TeacherResource\Pages;

use App\Filament\Resources\Schools\TeacherResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
