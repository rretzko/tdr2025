<?php

namespace App\Filament\Resources\EpaymentResource\Pages;

use App\Filament\Resources\EpaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEpayment extends CreateRecord
{
    protected static string $resource = EpaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
