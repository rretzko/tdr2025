<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigMembershipResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigMembershipResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVersionConfigMemberships extends ListRecords
{
    protected static string $resource = VersionConfigMembershipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
