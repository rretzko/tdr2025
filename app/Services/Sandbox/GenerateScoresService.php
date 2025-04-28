<?php

namespace App\Services\Sandbox;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;

/**
 * This model generates 213,600 queries for a population of 881 registrants (2025 NJ All-State Chorus)
 * using 212,601 models
 * High likelihood that refactoring can reduce this load
 * @todo Deep dive refactoring opportunity
 */
class GenerateScoresService
{
    private int $counter;
    private int $eventId;
    private array $scoreFactorMinMaxs;

    #[NoReturn] public function __construct(private readonly int $versionId)
    {
        set_time_limit(180);
        $this->eventId = Version::find($this->versionId)->event_id;
        $this->scoreFactorMinMaxs = [];

        $this->init();
    }

    public function getCounter(): int
    {
        return $this->counter;
    }

    #[NoReturn] private function init(): void
    {
        $start = now();
        Log::info('start: '.$start);
        //candidates.id, student_id, school_id, voice_part_id,
        //users.first_name, users.middle_name, users.last_name, users.suffix_name
        //schools.name
        //voice_parts.id, voice_parts.descr, voice_parts.abbr, 'voice_parts.order_by
        $registrants = $this->setRegistrants();

        //rooms.id, rooms.order_by,
        //judges.id, judges.judge_type
        // room_score_categories.score_category_id,
        // score_factors.id, score_factors.order_by
        $rooms = $this->setRooms();

//        $this->generateScores($registrants, $rooms);
        $this->counter = $this->generateBatchScores($registrants, $rooms);

        $end = now();
        Log::info('end: '.$end);
        $duration = $start->diffInSeconds($end);
        Log::info('duration: '.$duration);
    }

    private function generateBatchScores(array $registrants, array $rooms): int
    {
        $regCntr = 0;
        $data = [];

        $judgeOrderBys = [
            'head judge' => 1,
            'judge 2' => 2,
            'judge 3' => 3,
            'judge 4' => 4,
        ];

        //do not execute in production
        if (app('env') === 'local') {

            //clear current records
            DB::table('scores')
                ->where('version_id', $this->versionId)
                ->delete();

            //iterate through regisrants and rooms
            foreach ($registrants as $registrant) {

                $regCntr++;

                //filter rooms by voice_part_id
                $registrantRooms = $this->selectRooms($rooms, $registrant);

                foreach ($registrantRooms as $room) {

                    $this->setScoreFactorMinMaxs($room);

                    $scoreFactorId = $room['scoreFactorId'];

                    $max = $this->scoreFactorMinMaxs[$scoreFactorId]['max'];
                    $min = $this->scoreFactorMinMaxs[$scoreFactorId]['min'];

                    $roomJudgeIds = Room::find($room['roomId'])->judges->pluck('id')->toArray();


                    $data[] = [
                        'version_id' => $this->versionId,
                        'candidate_id' => $registrant['id'],
                        'student_id' => $registrant['student_id'],
                        'school_id' => $registrant['school_id'],
                        'score_category_id' => $room['scoreCategoryId'],
                        'score_category_order_by' => $room['roomScoreCategoryOrderBy'],
                        'score_factor_id' => $scoreFactorId,
                        'score_factor_order_by' => $room['scoreFactorOrderBy'],
                        'judge_id' => $room['judgeId'],
                        'judge_order_by' => $judgeOrderBys[$room['judgeType']],
                        'voice_part_id' => $registrant['voicePartId'],
                        'voice_part_order_by' => $registrant['voicePartOrderBy'],
                        'score' => rand($min, $max),
                        'created_at' => now(),  // if your table uses timestamps
                        'updated_at' => now(),
                    ];
                }

                //insert $data if count is multiple of 200 to manage memory
                if ((!($regCntr % 200)) || ($regCntr == count($registrants))) {
                    $batchSize = 200;
                    $chunks = array_chunk($data, $batchSize);

                    DB::transaction(function () use ($chunks) {
                        foreach ($chunks as $chunk) {
                            Score::insert($chunk);
                        }
                    });

                    //reset $data storage
                    $data = [];
                }
            }
        }

        return $regCntr;
    }

//    private function generateScores(array $registrants, array $rooms): int
//    {
//        $cntr = 0;
//
//        foreach ($registrants as $registrant) {
//
//            foreach ($rooms as $room) {
//
//                $cntr = $this->generateScore($registrant, $room);
//            }
//        }
//
//        return $cntr;
//    }

    /**
     * array:13 [▼ // app\Services\Sandbox\GenerateScoresService.php:52
     * "id" => 848519
     * "student_id" => 6844
     * "school_id" => 1986
     * "voice_part_id" => 63
     * "name" => "Alicia Fallon"
     * "first_name" => "Alicia"
     * "middle_name" => ""
     * "last_name" => "Fallon"
     * "suffix_name" => null
     * "schoolName" => "Washington Township High School"
     * "voicePartDescr" => "Soprano I"
     * "voicePartAbbr" => "SI"
     * "voicePartOrderBy" => 3
     * ]
     * array:7 [▼ // app\Services\Sandbox\GenerateScoresService.php:52
     * "roomId" => 85
     * "roomOrderBy" => 1
     * "judgeId" => 285
     * "judgeType" => "head judge"
     * "roomScoreCategoryId" => 470
     * "roomScoreCategoryOrderBy" => 1
     * "scoreFactorId" => 1
     * "scoreFactorOrderBy" => 1
     * ]
     * @param  array  $registrant
     * @param  array  $room
     * @return int
     */
//    private function generateScore(array $registrant, array $room): int
//    {
//        static $cntr = 0;
//
//        $judgeOrderBys = [
//            'head judge' => 1,
//            'judge 2' => 2,
//            'judge 3' => 3,
//            'judge 4' => 4,
//        ];
//
//        if (!$this->scoreExists($registrant, $room, $judgeOrderBys)) {
//
//            $cntr++;
//
//            $this->setScoreFactorMinMaxs($room);
//
//            $scoreFactorId = $room['scoreFactorId'];
//
//            $max = $this->scoreFactorMinMaxs[$scoreFactorId]['max'];
//            $min = $this->scoreFactorMinMaxs[$scoreFactorId]['min'];
//
//            Score::create([
//                'version_id' => $this->versionId,
//                'candidate_id' => $registrant['id'],
//                'student_id' => $registrant['student_id'],
//                'school_id' => $registrant['school_id'],
//                'score_category_id' => $room['scoreCategoryId'],
//                'score_category_order_by' => $room['roomScoreCategoryOrderBy'],
//                'score_factor_id' => $scoreFactorId,
//                'score_factor_order_by' => $room['scoreFactorOrderBy'],
//                'judge_id' => $room['judgeId'],
//                'judge_order_by' => $judgeOrderBys[$room['judgeType']],
//                'voice_part_id' => $registrant['voicePartId'],
//                'voice_part_order_by' => $registrant['voicePartOrderBy'],
//                'score' => rand($min, $max),
//            ]);
//        }
//
//        return $cntr;
//    }

    private function scoreExists(array $registrant, array $room, array $judgeOrderBys): bool
    {
        return Score::query()
            ->where('version_id', $this->versionId)
            ->where('candidate_id', $registrant['id'])
            ->where('student_id', $registrant['student_id'])
            ->where('school_id', $registrant['school_id'])
            ->where('score_category_id', $room['scoreCategoryId'])
            ->where('score_category_order_by', $room['roomScoreCategoryOrderBy'])
            ->where('score_factor_id', $room['scoreFactorId'])
            ->where('score_factor_order_by', $room['scoreFactorOrderBy'])
            ->where('judge_id', $room['judgeId'])
            ->where('judge_order_by', $judgeOrderBys[$room['judgeType']])
            ->where('voice_part_id', $registrant['voicePartId'])
            ->where('voice_part_order_by', $registrant['voicePartOrderBy'])
            ->exists();
    }

    private function selectRooms(array $rooms, array $registrant): array
    {
        return array_values(array_filter($rooms, function ($room) use ($registrant) {
            return $room['voice_part_id'] === $registrant['voicePartId'];
        }));
    }

    private function setRegistrants(): array
    {
        return Candidate::query()
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->where('candidates.version_id', $this->versionId)
            ->where('status', 'registered')
            ->select('candidates.id', 'candidates.student_id', 'candidates.school_id',
                'candidates.voice_part_id',
                'users.name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix_name',
                'schools.name as schoolName',
                'voice_parts.id as voicePartId', 'voice_parts.descr as voicePartDescr',
                'voice_parts.abbr as voicePartAbbr',
                'voice_parts.order_by as voicePartOrderBy')
            ->get()
            ->toArray();
    }

    private function setRooms(): array
    {
        return Room::query()
            ->join('room_score_categories', 'room_score_categories.room_id', '=', 'rooms.id')
            ->join('score_categories', 'score_categories.id', '=', 'room_score_categories.score_category_id')
            ->join('score_factors', 'score_factors.score_category_id', '=', 'room_score_categories.score_category_id')
            ->join('judges', 'judges.room_id', '=', 'rooms.id')
            ->join('room_voice_parts', 'room_voice_parts.room_id', '=', 'rooms.id')
            ->where('rooms.version_id', $this->versionId)
            ->where('score_factors.event_id', $this->eventId)
            ->where('judges.status_type', 'assigned')
            ->select('rooms.id as roomId', 'rooms.order_by as roomOrderBy',
                'judges.id as judgeId', 'judges.judge_type as judgeType',
                'room_score_categories.id as roomScoreCategoryId',
                'room_score_categories.score_category_id AS scoreCategoryId',
                'score_categories.order_by as roomScoreCategoryOrderBy',
                'score_factors.id as scoreFactorId', 'score_factors.order_by as scoreFactorOrderBy',
                'room_voice_parts.voice_part_id'
            )
            ->orderBy('rooms.order_by')
            ->orderBy('judgeType')
            ->orderBy('score_factors.order_by')
            ->get()
            ->toArray();
    }

    private function setScoreFactorMinMaxs(array $room): void
    {
        $factor = ScoreFactor::find($room['scoreFactorId'])->factor;
        $query = ScoreFactor::query()
            ->where('event_id', $this->eventId)
            ->where('score_category_id', $room['scoreCategoryId'])
            ->where('factor', $factor);

        //initialize array
        if (!$this->scoreFactorMinMaxs) {

            $worst = $query->value('worst');
            $best = $query->value('best');

            $this->scoreFactorMinMaxs[$room['scoreFactorId']]['min'] = ($worst > $best) ? $best : $worst;
            $this->scoreFactorMinMaxs[$room['scoreFactorId']]['max'] = ($worst > $best) ? $worst : $best;
        }

        //assign min value
        if ((!array_key_exists($room['scoreFactorId'], $this->scoreFactorMinMaxs)) ||
            (!array_key_exists('min', $this->scoreFactorMinMaxs[$room['scoreFactorId']]))) {
            $worst = $query->value('worst');
            $best = $query->value('best');
            $this->scoreFactorMinMaxs[$room['scoreFactorId']]['min'] = ($worst > $best) ? $best : $worst;
        }

        //assign max value
        if ((!array_key_exists($room['scoreFactorId'], $this->scoreFactorMinMaxs)) ||
            (!array_key_exists('max', $this->scoreFactorMinMaxs[$room['scoreFactorId']]))) {
            $worst = $query->value('worst');
            $best = $query->value('best');
            $this->scoreFactorMinMaxs[$room['scoreFactorId']]['max'] = ($worst > $best) ? $worst : $best;
        }

    }
}
