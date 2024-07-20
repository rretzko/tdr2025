<?php

namespace App\Filament\Resources\Events\Versions\VersionRoleResource\Pages;

use App\Filament\Resources\Events\Versions\VersionRoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVersionRoles extends ListRecords
{
    protected static string $resource = VersionRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
