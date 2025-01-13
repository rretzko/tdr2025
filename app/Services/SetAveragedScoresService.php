<?php

namespace App\Services;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\Judge;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Schools\Teacher;
use App\Models\Students\VoicePart;

class SetAveragedScoresService
{
    private bool $earlyExit = false;
    private bool $useFloor = true; //round the average down (true) or up (false)
    private int $isStudentOfJudgeId = 0;
    private VersionConfigAdjudication $vca;

    public function __construct(
        private readonly Room $room,
        private readonly Candidate $candidate
    ) {
        $this->vca = VersionConfigAdjudication::where('version_id', $this->candidate->version_id)->first();
        $this->useFloor = $this->vca->scores_ascending;
        $this->init();
    }

    private function init(): void
    {
        //determine if averaged scores are required by version
        $this->averagedScoresAreRequiredByVersion();
        //determine if $candidate isStudentOf a judge in the room
        $this->candidateIsStudentOfRoomJudge();
        //determine the total number of scores expected for the room
        $maxScoreCount = $this->room->getMaxScoreCount();
        //determine the total number of judges in the room
        $judgeCount = $this->room->judges->count();
        //determine if all other judge scores have been populated
        $this->allScoresEntered($maxScoreCount, $judgeCount);
        //insert averaged scores of other judges for the judge who the candidate isStudentOf
        $this->insertAveragedScores();
    }

    /**
     * Set $this->earlyExit to true if $vca->averaged_scores === 0
     * @return void
     */
    private function averagedScoresAreRequiredByVersion(): void
    {
        if (!$this->earlyExit) {
            $this->earlyExit = !$this->vca->averaged_scores;
        }
    }

    /**
     * Set $this->earlyExit to true if candidate->teacher_id is NOT in array $judges
     * @return void
     */
    private function candidateIsStudentOfRoomJudge(): void
    {
        if (!$this->earlyExit) {
            $judges = $this->room->judges->pluck('user_id')->toArray();
            $this->earlyExit = !in_array($this->candidate->teacher_id, $judges);
        }
    }

    /**
     *  Set $this->earlyExit to true if $currentScoreCount !== $expectedScoreCount
     * @param  int  $maxScoreCount
     * @param  int  $judgeCount
     * @return void
     */
    private function allScoresEntered(int $maxScoreCount, int $judgeCount): void
    {
        if (!$this->earlyExit) {
            $expectedScoreCount = ($maxScoreCount / ($judgeCount - 1));
            $currentScoreCount = Score::where('candidate_id', $this->candidate->id)->count();
            $this->earlyExit = (!$currentScoreCount == $currentScoreCount);
        }
    }

    private function insertAveragedScores(): void
    {
        if (!$this->earlyExit) {
            $userId = Teacher::find($this->candidate->teacher_id)->user_id;
            $missingJudge = Judge::where('version_id', $this->candidate->version_id)
                ->where('room_id', $this->room->id)
                ->where('user_id', $userId)
                ->first();
            $missingJudgeOrderBys = [
                'head judge' => 1,
                'judge 2' => 2,
                'judge 3' => 3,
                'judge 4' => 4,
                'judge monitor' => 5,
            ];
            $voicePart = VoicePart::find($this->candidate->voice_part_id);
            $scoringFactors = $this->room->scoringFactors;

            foreach ($scoringFactors as $factor) {
                $raw = Score::query()
                    ->where('candidate_id', $this->candidate->id)
                    ->where('score_category_id', $factor->score_category_id)
                    ->where('score_factor_id', $factor->id)
                    ->pluck('score')
                    ->avg();

                $avg = $this->useFloor ? floor($raw) : ceil($raw);

                $scoreCategory = ScoreCategory::find($factor->score_category_id);

                Score::updateOrCreate(
                    [
                        'version_id' => $this->candidate->version_id,
                        'candidate_id' => $this->candidate->id,
                        'student_id' => $this->candidate->student_id,
                        'school_id' => $this->candidate->school_id,
                        'score_category_id' => $scoreCategory->id,
                        'score_category_order_by' => $scoreCategory->order_by,
                        'score_factor_id' => $factor->id,
                        'score_factor_order_by' => $factor->order_by,
                        'judge_id' => $missingJudge->id,
                        'judge_order_by' => $missingJudgeOrderBys[$missingJudge->judge_type],
                        'voice_part_id' => $voicePart->id,
                        'voice_part_order_by' => $voicePart->order_by,
                    ],
                    [
                        'score' => $avg,
                    ]
                );

            }

        }
    }

    private function getScores()
    {
        $judgeIds = $this->room->judges->pluck('id')->toArray();
        return Score::query()
            ->where('candidate_id', $this->candidate->id)
            ->whereIn('judge_id', $judgeIds)
            ->orderBy('score_category_id')
            ->orderBy('score_factor_id')
            ->orderBy('judge_id')
            ->get();
    }
}
