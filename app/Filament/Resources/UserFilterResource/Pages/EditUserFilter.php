<?php

namespace App\Filament\Resources\UserFilterResource\Pages;

use App\Filament\Resources\UserFilterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUserFilter extends EditRecord
{
    protected static string $resource = UserFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
