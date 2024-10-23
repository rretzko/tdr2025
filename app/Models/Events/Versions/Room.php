<?php

namespace App\Models\Events\Versions;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Scoring\Judge;
use App\Models\Events\Versions\Scoring\RoomScoreCategory;
use App\Models\Events\Versions\Scoring\RoomVoicePart;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Services\CandidateAdjudicationStatusService;
use App\Services\JudgeHasCompletedScoringCandidateService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Can;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'room_name',
        'tolerance',
        'order_by',
    ];

    public function checkScoreTolerance(Candidate $candidate): bool
    {
        $scores = $this->getTotalScoresArray($candidate); //['judge_id' => 'totalScore']
        $maxScore = count($scores) ? max($scores) : 0;
        $minScore = count($scores) ? min($scores) : 0;

        return (($maxScore - $minScore) <= $this->tolerance);
    }

    public function judges(): HasMany
    {
        return $this->hasMany(Judge::class)
            ->with('user');
    }

    public function roomScoreCategories(): HasMany
    {
        return $this->hasMany(RoomScoreCategory::class);
    }

    /**
     * ex. array:48 [▼ // app\Models\Events\Versions\Room.php:62
     *  0 => {#2327 ▼
     *  +"id": 822897
     *  +"ref": "82-2897"
     *  +"descr": "Tenor I"
     *  +"abbr": "TI"
     *  +"order_by": 9
     *  }
     *  1 => {#2326 ▼
     *  +"id": 823890
     *  +"ref": "82-3890"
     *  +"descr": "Tenor I"
     *  +"abbr": "TI"
     *  +"order_by": 9
     * ...
     * ]
     * @return array
     */
    public function getAdjudicationButtonsAllArrayAttribute(): array
    {
        $candidates = $this->candidatesSql();

        $status = $this->addStatusCoding($candidates);

        $scoring = $this->addScoringCompleted($status);

        return $this->addToleranceCoding($scoring);

    }

    public function getAdjudicationButtonsIncompleteArrayAttribute(): array
    {
        $candidates = $this->candidatesSql(true);

        $status = $this->addStatusCoding($candidates);

        $scoring = $this->addScoringCompleted($status);

        return $this->addToleranceCoding($scoring);
    }

    public function getRegistrantsByIdAttribute(): Collection
    {
        return Candidate::query()
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->where('version_id', $this->version_id)
            ->whereIn('voice_part_id', $this->voicePartIds)
            ->where('status', 'registered')
            ->select('candidates.id', 'voice_parts.abbr')
            ->orderBy('id')
            ->get();
    }

    /**
     * @return array of [id, voicePartAbbr]
     */
    public function getRegistrantsByIdArrayAttribute(): array
    {
        return Candidate::query()
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->where('version_id', $this->version_id)
            ->whereIn('voice_part_id', $this->voicePartIds)
            ->where('status', 'registered')
            ->select('candidates.id', 'voice_parts.abbr', 'voice_parts.order_by')
            ->orderBy('voice_parts.order_by')
            ->orderBy('candidates.id')
            ->get()
            ->toArray();
    }

    public function getScores(Candidate $candidate): array
    {
        $a = [];
        $judges = $this->judges()->with('user')->where('judge_type', 'LIKE', '%judge%')->get()->sortBy(function ($judge
        ) {
            return $judge->user->last_name;
        });

        foreach ($judges as $judge) {
            $a[] = [
                'judgeName' => $judge['user']->last_name,
                'judgeUserId' => $judge['user']->id,
                'scores' => $this->getCandidateScoresByJudge($candidate, $judge),
            ];
        }

        return $a;
    }

    private function getCandidateScoresByJudge(Candidate $candidate, Judge $judge): array
    {
        return DB::table('scores')
            ->where('candidate_id', $candidate->id)
            ->where('judge_id', $judge->id)
            ->select('score')
            ->orderBy('score_factor_order_by')
            ->pluck('score')
            ->toArray() ?? [];
    }

    /**
     * @return array [scoreCategory, colSpan]
     */
    public function getScoringCategoriesAttribute(): array
    {
        $categoryIds = $this->roomScoreCategories->pluck('score_category_id')->toArray();

        return DB::table('score_categories')
            ->join('score_factors', 'score_factors.score_category_id', '=', 'score_categories.id')
            ->whereIn('score_categories.id', $categoryIds)
            ->selectRaw('score_categories.descr, COUNT(score_factors.id) AS colSpan')
            ->orderBy('score_categories.order_by')
            ->groupBy('score_categories.id', 'score_categories.descr')
            ->get()
            ->toArray();
    }

    public function getScoringFactorsAttribute()
    {
        $roomScoreCategoriesIds = $this->roomScoreCategories->pluck('score_category_id')->toArray();

        return ScoreFactor::query()
            ->with('scoreCategory')
            ->whereIn('score_category_id', $roomScoreCategoriesIds)
            ->orderBy('score_factors.order_by')
            ->get();
    }

    public function getVoicePartIdsAttribute(): array
    {
        return $this->roomVoiceParts->pluck('voice_part_id')->toArray();
    }

    public function roomVoiceParts(): HasMany
    {
        return $this->hasMany(RoomVoicePart::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    private function addScoringCompleted(array $candidates): array
    {
        foreach ($candidates as $candidate) {

            $candidate->scoringCompleted = JudgeHasCompletedScoringCandidateService::scoringCompleted($candidate->id,
                $this);
        }

        return $candidates;
    }

    private function addStatusCoding(array $candidates): array
    {
        foreach ($candidates as $candidate) {
            $candidate->status = CandidateAdjudicationStatusService::getRoomStatus($candidate->id, $this);
        }

        return $candidates;
    }

    private function addToleranceCoding(array $candidates): array
    {
        foreach ($candidates as $candidate) {
            $tolerance = $this->checkScoreTolerance(Candidate::find($candidate->id));

            $candidate->tolerance = ($tolerance)
                ? ''
                : '*';
        }

        return $candidates;

        return [];
    }

    private function candidatesSql($incomplete = false): array
    {
        $voicePartIds = RoomVoicePart::where('room_id', $this->id)
            ->pluck('voice_part_id')
            ->toArray();

        return ($incomplete)
            ? $this->getIncompleteCandidatesSql($voicePartIds)
            : $this->getAllCandidatesSql($voicePartIds);
    }

    private function getTotalScoresArray(Candidate $candidate): array
    {
        $judgeIds = $this->judges->pluck('id')->toArray();

        return DB::table('scores')
            ->where('candidate_id', $candidate->id)
            ->whereIn('judge_id', $judgeIds)
            ->select('scores.judge_id')
            ->selectRaw('SUM(scores.score) AS totalScore')
            ->orderBy('totalScore', 'desc')
            ->groupBy('scores.judge_id')
            ->pluck('totalScore', 'judge_id')
            ->toArray();
    }

    private function getAllCandidatesSql(array $voicePartIds): array
    {
        return DB::table('candidates')
            ->join('room_voice_parts', 'room_voice_parts.voice_part_id', '=', 'candidates.voice_part_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->where('candidates.version_id', $this->version_id)
            ->whereIn('candidates.voice_part_id', $voicePartIds)
            ->where('candidates.status', 'registered')
            ->distinct('candidates.id')
            ->select('candidates.id', 'candidates.ref', 'voice_parts.descr', 'voice_parts.abbr', 'voice_parts.order_by')
            ->orderBy('voice_parts.order_by')
            ->orderBy('candidates.id')
            ->get()
            ->toArray();
    }

    private function getIncompleteCandidatesSql(array $voicePartIds): array
    {
        $judgeCount = $this->judges->count();
        $scoreFactorCount = $this->scoringFactors->count();
        $maxScoreCount = ($judgeCount * $scoreFactorCount);

        return DB::table('candidates')
            ->join('room_voice_parts', 'room_voice_parts.voice_part_id', '=', 'candidates.voice_part_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->join('audition_results', 'audition_results.candidate_id', '=', 'candidates.id')
            ->where('candidates.version_id', $this->version_id)
            ->whereIn('candidates.voice_part_id', $voicePartIds)
            ->where('candidates.status', 'registered')
            ->where('audition_results.score_count', '!=', $maxScoreCount)
            ->distinct()
            ->select('candidates.id', 'candidates.ref', 'voice_parts.descr', 'voice_parts.abbr', 'voice_parts.order_by')
            ->orderBy('voice_parts.order_by')
            ->orderBy('candidates.id')
            ->get()
            ->toArray();
    }

}
