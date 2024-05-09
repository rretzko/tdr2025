<?php

namespace App\Filament\Resources\Schools\SchoolTeacherResource\Pages;

use App\Filament\Resources\Schools\SchoolTeacherResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSchoolTeachers extends ListRecords
{
    protected static string $resource = SchoolTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
