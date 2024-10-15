<?php

namespace App\Services;

use App\Models\Events\Versions\Room;

class JudgeHasCompletedScoringCandidateService
{
    public static function scoringCompleted(int $candidateId, Room $room): bool
    {
        return false;
    }
}
