<?php

namespace App\Filament\Resources\Events\Versions\JudgeResource\Pages;

use App\Filament\Resources\Events\Versions\JudgeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJudge extends CreateRecord
{
    protected static string $resource = JudgeResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
