<?php

namespace App\Filament\Resources\Events\Versions\Participations\StudentPaymentResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\StudentPaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentPayment extends EditRecord
{
    protected static string $resource = StudentPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
