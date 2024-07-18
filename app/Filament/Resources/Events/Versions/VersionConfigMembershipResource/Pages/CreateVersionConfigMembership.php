<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigMembershipResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigMembershipResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVersionConfigMembership extends CreateRecord
{
    protected static string $resource = VersionConfigMembershipResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
