<?php

namespace App\Filament\Resources\StudentTeacherResource\Pages;

use App\Filament\Resources\StudentTeacherResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentTeachers extends ListRecords
{
    protected static string $resource = StudentTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
