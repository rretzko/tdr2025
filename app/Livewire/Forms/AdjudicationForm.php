<?php

namespace App\Livewire\Forms;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Scoring\Judge;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Students\VoicePart;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AdjudicationForm extends Form
{
    public Candidate $candidate;
    public Collection $factors;
    public string $ref = '';
    public int $roomTolerance = 0;
    public array $scores = [];
    public string $sysId = "";
    public int $totalScore = 0;

    public function setCandidate(Candidate $candidate, Room $room, Judge $judge): void
    {
        $this->candidate = $candidate;

        $this->sysId = $candidate->id;

        $this->ref = $candidate->ref;

        $this->roomTolerance = $room->tolerance;

        $this->factors = $room->scoringFactors;

        $scores = Score::query()
            ->where('candidate_id', $this->sysId)
            ->where('judge_id', $judge->id)
            ->pluck('score', 'id')
            ->toArray() ?? [];

        foreach ($this->factors as $factor) {
            $this->scores[$factor->id] = $scores[$factor->id] ?? ($factor->best - 1);
        }

    }

    public function updateScores(): void
    {
        foreach ($this->scores as $key => $score) {

            $scoreFactor = ScoreFactor::find($key);
            $scoreCategory = ScoreCategory::find($scoreFactor->score_category_id);
            $judge = Judge::where('version_id', $this->candidate->version_id)->where('user_id', auth()->id())->first();
            $judgeOrderBy = ['head judge' => 1, 'judge 2' => 2, 'judge 3' => 3, 'judge 4' => 4, 'judge monitor' => 5];
            $voicePart = VoicePart::find($this->candidate->voice_part_id);
            Score::updateOrCreate(
                [
                    'candidate_id' => $this->candidate->id,
                    'version_id' => $this->candidate->version_id,
                    'student_id' => $this->candidate->student_id,
                    'school_id' => $this->candidate->school_id,
                    'score_category_id' => $scoreFactor->score_category_id,
                    'score_category_order_by' => $scoreCategory->order_by,
                    'score_factor_id' => $scoreFactor->id,
                    'judge_id' => $judge->id,
                    'judge_order_by' => $judgeOrderBy[$judge['role']],
                    'voice_part_id' => $this->candidate->voice_part_id,
                    'voice_part_order_by' => $voicePart->order_by,
                ],
                [
                    'score' => $score,
                ]
            );
        }
    }
}
