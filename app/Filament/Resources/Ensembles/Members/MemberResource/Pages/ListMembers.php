<?php

namespace App\Filament\Resources\Ensembles\Members\MemberResource\Pages;

use App\Filament\Resources\Ensembles\Members\MemberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
