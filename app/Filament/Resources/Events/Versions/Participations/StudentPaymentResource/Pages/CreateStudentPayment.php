<?php

namespace App\Filament\Resources\Events\Versions\Participations\StudentPaymentResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\StudentPaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentPayment extends CreateRecord
{
    protected static string $resource = StudentPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
