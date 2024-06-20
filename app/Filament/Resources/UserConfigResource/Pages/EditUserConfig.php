<?php

namespace App\Filament\Resources\UserConfigResource\Pages;

use App\Filament\Resources\UserConfigResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditUserConfig extends EditRecord
{
    protected static string $resource = UserConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
