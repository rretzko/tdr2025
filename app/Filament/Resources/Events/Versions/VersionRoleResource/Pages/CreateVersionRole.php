<?php

namespace App\Filament\Resources\Events\Versions\VersionRoleResource\Pages;

use App\Filament\Resources\Events\Versions\VersionRoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVersionRole extends CreateRecord
{
    protected static string $resource = VersionRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
