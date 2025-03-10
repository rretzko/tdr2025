<?php

namespace App\Filament\Resources\Events\Versions\Scoring\JudgeResource\Pages;

use App\Filament\Resources\Events\Versions\Scoring\JudgeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJudge extends EditRecord
{
    protected static string $resource = JudgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
