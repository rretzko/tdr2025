<?php

namespace App\Filament\Resources\SchoolStudentResource\Pages;

use App\Filament\Resources\SchoolStudentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSchoolStudent extends EditRecord
{
    protected static string $resource = SchoolStudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
