<?php

namespace App\Filament\Resources\Events\Versions\TeacherPaymentResource\Pages;

use App\Filament\Resources\Events\Versions\TeacherPaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTeacherPayment extends EditRecord
{
    protected static string $resource = TeacherPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
