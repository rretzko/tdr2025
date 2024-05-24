<?php

namespace App\Filament\Resources\Schools\SchoolGradeResource\Pages;

use App\Filament\Resources\Schools\SchoolGradeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSchoolGrade extends CreateRecord
{
    protected static string $resource = SchoolGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
