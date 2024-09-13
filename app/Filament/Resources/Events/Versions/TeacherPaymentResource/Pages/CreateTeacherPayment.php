<?php

namespace App\Filament\Resources\Events\Versions\TeacherPaymentResource\Pages;

use App\Filament\Resources\Events\Versions\TeacherPaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacherPayment extends CreateRecord
{
    protected static string $resource = TeacherPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
