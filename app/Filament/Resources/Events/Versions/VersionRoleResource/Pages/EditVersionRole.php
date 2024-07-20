<?php

namespace App\Filament\Resources\Events\Versions\VersionRoleResource\Pages;

use App\Filament\Resources\Events\Versions\VersionRoleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditVersionRole extends EditRecord
{
    protected static string $resource = VersionRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
