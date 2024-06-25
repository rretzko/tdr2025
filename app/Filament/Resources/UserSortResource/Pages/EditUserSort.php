<?php

namespace App\Filament\Resources\UserSortResource\Pages;

use App\Filament\Resources\UserSortResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditUserSort extends EditRecord
{
    protected static string $resource = UserSortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
