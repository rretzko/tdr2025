<?php

namespace App\Filament\Resources\ePaymentCredentialsResource\Pages;

use App\Filament\Resources\epaymentCredentialsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEpaymentCredentials extends EditRecord
{
    protected static string $resource = epaymentCredentialsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
