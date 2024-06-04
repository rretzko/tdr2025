<?php

namespace App\Filament\Resources\SchoolStudentResource\Pages;

use App\Filament\Resources\SchoolStudentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSchoolStudents extends ListRecords
{
    protected static string $resource = SchoolStudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
