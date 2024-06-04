<?php

namespace App\Filament\Resources\SchoolStudentResource\Pages;

use App\Filament\Resources\SchoolStudentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSchoolStudent extends CreateRecord
{
    protected static string $resource = SchoolStudentResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
