<?php

namespace App\Services;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use Illuminate\Support\Facades\Log;

class CandidateAdjudicationStatusService
{
    private static string $candidateId;
    private static string $status;
    private static Version $version;
    private static int $versionId;

    public static function getStatus(int $candidateId): string
    {
        self::$candidateId = $candidateId;

        self::init($candidateId);

        return self::$status;
    }

    private static function init()
    {
        $candidate = Candidate::find(self::$candidateId);
        self::$version = Version::find($candidate->version_id);
        self::$versionId = self::$version->id;
        $scoreCount = self::getScoreCount();
        $maxScoreCount = self::getMaxScoreCount();
        Log::info('scoreCount: '.$scoreCount.' | maxScoreCount: '.$maxScoreCount);
        if (!$scoreCount) {
            self::$status = 'pending';
        } elseif ($scoreCount === $maxScoreCount) {
            self::$status = 'completed';
        } elseif ($scoreCount < $maxScoreCount) {
            self::$status = 'wip';
        } else {
            self::$status = 'errors';
        }
    }

    private static function getScoreCount(): int
    {
        return Score::query()
            ->where('candidate_id', self::$candidateId)
            ->count() ?? 0;
    }

    private static function getMaxScoreCount(): int
    {
        $factorCount = ScoreFactor::query()
            ->where('version_id', self::$versionId)
            ->count('id');
        if (!$factorCount) {
            $factorCount = ScoreFactor::query()
                ->where('event_id', self::$version->event_id)
                ->count('id');
        }
        $judgeCount = VersionConfigAdjudication::query()
            ->where('version_id', self::$versionId)
            ->value('judge_per_room_count');

        return ($factorCount * $judgeCount);
    }
}
