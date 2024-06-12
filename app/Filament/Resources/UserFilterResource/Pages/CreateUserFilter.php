<?php

namespace App\Filament\Resources\UserFilterResource\Pages;

use App\Filament\Resources\UserFilterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserFilter extends CreateRecord
{
    protected static string $resource = UserFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
