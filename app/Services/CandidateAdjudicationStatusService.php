<?php

namespace App\Services;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\RoomScoreCategory;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use Illuminate\Support\Facades\Log;

class CandidateAdjudicationStatusService
{
    private static string $candidateId;
    private static Room|bool $room = false;
    private static string $status;
    private static Version $version;
    private static int $versionId;

    public static function getRoomStatus(int $candidateId, Room $room): string
    {
        self::$room = $room;

        self::$candidateId = $candidateId;

        self::init($candidateId);

        return self::$status;
    }

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

//        Log::info('scoreCount: '.$scoreCount.' | maxScoreCount: '.$maxScoreCount);
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

    private static function getRoomMaxScoreCount(int $judgeCount): int
    {
        $roomCategories = RoomScoreCategory::query()
            ->where('room_id', self::$room->id)
            ->pluck('score_category_id')
            ->toArray();

        $factorCount = ScoreFactor::query()
            ->where('version_id', self::$versionId)
            ->whereIn('score_category_id', $roomCategories)
            ->count('id');

        if (!$factorCount) {
            $factorCount = ScoreFactor::query()
                ->where('event_id', self::$version->event_id)
                ->whereIn('score_category_id', $roomCategories)
                ->count('id');
        }

        return ($factorCount * $judgeCount);
    }

    private static function getRoomScoreCount(): int
    {
        $judgeIds = self::$room->judges->pluck('id')->toArray();

        return Score::query()
            ->where('candidate_id', self::$candidateId)
            ->whereIn('judge_id', $judgeIds)
            ->count() ?? 0;
    }

    /**
     * @todo Review lines 112/114 when running \App\Services\ScoreSeederService and \App\Services\AuditionResultService
     * @return int
     *
     */
    private static function getScoreCount(): int
    {
        if (self::$room) {
            return self::getRoomScoreCount();
        }

        return Score::query()
            ->where('candidate_id', self::$candidateId)
            ->count() ?? 0;
    }

    private static function getMaxScoreCount(): int
    {
        $judgeCount = VersionConfigAdjudication::query()
            ->where('version_id', self::$versionId)
            ->value('judge_per_room_count');

        if (self::$room) {
            return self::getRoomMaxScoreCount($judgeCount);
        }

        $factorCount = ScoreFactor::query()
            ->where('version_id', self::$versionId)
            ->count('id');

        if (!$factorCount) {
            $factorCount = ScoreFactor::query()
                ->where('event_id', self::$version->event_id)
                ->count('id');
        }

        return ($factorCount * $judgeCount);
    }
}
