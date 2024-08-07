<?php

namespace App\Filament\Resources\Events\Versions\Participations\StudentPaymentResource\Pages;

use App\Filament\Resources\Events\Versions\Participations\StudentPaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentPayments extends ListRecords
{
    protected static string $resource = StudentPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
