<?php

namespace App\Filament\Resources\Schools\SchoolGradeResource\Pages;

use App\Filament\Resources\Schools\SchoolGradeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSchoolGrades extends ListRecords
{
    protected static string $resource = SchoolGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
