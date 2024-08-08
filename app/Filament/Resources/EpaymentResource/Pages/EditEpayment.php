<?php

namespace App\Filament\Resources\EpaymentResource\Pages;

use App\Filament\Resources\EpaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEpayment extends EditRecord
{
    protected static string $resource = EpaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
