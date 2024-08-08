<?php

namespace App\Filament\Resources\EpaymentResource\Pages;

use App\Filament\Resources\EpaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEpayments extends ListRecords
{
    protected static string $resource = EpaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
