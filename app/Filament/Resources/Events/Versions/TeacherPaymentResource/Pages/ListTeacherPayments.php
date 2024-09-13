<?php

namespace App\Filament\Resources\Events\Versions\TeacherPaymentResource\Pages;

use App\Filament\Resources\Events\Versions\TeacherPaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTeacherPayments extends ListRecords
{
    protected static string $resource = TeacherPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
