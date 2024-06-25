<?php

namespace App\Filament\Resources\UserSortResource\Pages;

use App\Filament\Resources\UserSortResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserSort extends CreateRecord
{
    protected static string $resource = UserSortResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
