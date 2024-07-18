<?php

namespace App\Filament\Resources\Events\Versions\VersionConfigMembershipResource\Pages;

use App\Filament\Resources\Events\Versions\VersionConfigMembershipResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVersionConfigMembership extends EditRecord
{
    protected static string $resource = VersionConfigMembershipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
