<?php

namespace App\Services;

use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\Judge;
use App\Models\Events\Versions\Scoring\Score;

class JudgeHasCompletedScoringCandidateService
{
    public static function scoringCompleted(int $candidateId, Room $room): bool
    {
        $judgeId = Judge::query()
            ->where('room_id', $room->id)
            ->where('user_id', auth()->id())
            ->value('id');

        $factorCount = $room->scoringFactors->count();

        $scoreCount = Score::query()
            ->where('candidate_id', $candidateId)
            ->where('judge_id', $judgeId)
            ->count('id');

        return $factorCount === $scoreCount;
    }
}
