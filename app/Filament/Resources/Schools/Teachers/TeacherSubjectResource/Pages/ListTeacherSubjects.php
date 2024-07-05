<?php

namespace App\Filament\Resources\Schools\Teachers\TeacherSubjectResource\Pages;

use App\Filament\Resources\Schools\Teachers\TeacherSubjectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTeacherSubjects extends ListRecords
{
    protected static string $resource = TeacherSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
