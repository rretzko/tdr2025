<?php

namespace App\Services;

use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\VersionConfigAdjudication;

class MaxScoreCountService
{
    public static function getMaxScoreCount(VersionConfigAdjudication $versionConfigAdjudication): int
    {
        $judgeCount = $versionConfigAdjudication->judge_per_room_count;
        $scoreFactor = new ScoreFactor();
        $factorCount = $scoreFactor->getCountByVersionId($versionConfigAdjudication->version_id);

        return ($judgeCount * $factorCount);
    }
}
