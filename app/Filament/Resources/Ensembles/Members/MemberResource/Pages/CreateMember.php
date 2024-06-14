<?php

namespace App\Filament\Resources\Ensembles\Members\MemberResource\Pages;

use App\Filament\Resources\Ensembles\Members\MemberResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
