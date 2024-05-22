<?php

namespace App\Filament\Resources\PageViewResource\Pages;

use App\Filament\Resources\PageViewResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePageView extends CreateRecord
{
    protected static string $resource = PageViewResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
