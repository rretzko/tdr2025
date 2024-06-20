<?php

namespace App\Filament\Resources\UserConfigResource\Pages;

use App\Filament\Resources\UserConfigResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserConfig extends CreateRecord
{
    protected static string $resource = UserConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
