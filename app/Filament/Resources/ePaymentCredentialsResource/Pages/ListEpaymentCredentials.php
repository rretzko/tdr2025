<?php

namespace App\Filament\Resources\ePaymentCredentialsResource\Pages;

use App\Filament\Resources\epaymentCredentialsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEpaymentCredentials extends ListRecords
{
    protected static string $resource = epaymentCredentialsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
