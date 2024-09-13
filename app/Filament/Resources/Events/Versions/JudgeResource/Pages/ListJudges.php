<?php

namespace App\Filament\Resources\Events\Versions\JudgeResource\Pages;

use App\Filament\Resources\Events\Versions\JudgeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJudges extends ListRecords
{
    protected static string $resource = JudgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
