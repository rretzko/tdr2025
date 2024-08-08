<?php

namespace App\Filament\Resources\ePaymentCredentialsResource\Pages;

use App\Filament\Resources\epaymentCredentialsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateePaymentCredentials extends CreateRecord
{
    protected static string $resource = epaymentCredentialsResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
