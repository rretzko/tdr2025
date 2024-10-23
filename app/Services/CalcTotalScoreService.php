<?php

namespace App\Services;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use Illuminate\Support\Collection;

class CalcTotalScoreService
{
    private int $candidateId = 0;
    private int $eventId = 0;
    private Collection $scoreFactors;
    private Collection $scores;
    private int $totalScore = 0;
    private Version $version;
    private int $versionId = 0;

    public function __construct(private readonly Candidate $candidate)
    {
        $this->candidateId = $this->candidate->id;
        $this->versionId = $this->candidate->version_id;
        $this->version = Version::find($this->versionId);
        $this->eventId = $this->version->event_id;

        $this->scores = $this->getScores();

        $this->scoreFactors = $this->getScoreFactors();
    }

    private function getScores(): Collection
    {
        return Score::query()
            ->where('candidate_id', $this->candidateId)
            ->orderBy('score_factor_order_by')
            ->select('id', 'score', 'score_factor_id', 'score_factor_order_by')
            ->get() ?? collect();
    }

    private function getScoreFactors(): Collection
    {
        $scoreFactors = ScoreFactor::query()
            ->where('version_id', $this->versionId)
            ->orderBy('order_by')
            ->select('id', 'multiplier', 'order_by')
            ->get();

        if ($scoreFactors->isNotEmpty()) {
            return $scoreFactors;
        }

        return ScoreFactor::query()
            ->where('event_id', $this->eventId)
            ->orderBy('order_by')
            ->select('id', 'multiplier', 'order_by')
            ->pluck('multiplier', 'id') ?? collect();
    }

    public function totalScore(): int
    {
        return $this->calcTotalScore();
    }

    private function calcTotalScore(): int
    {
        $total = 0;

        // Create a map of score factors for quick lookup
//        $scoreFactorMap = $this->scoreFactors->pluck('multiplier', 'id');

        foreach ($this->scores as $score) {
            $factorScore = $score->score;
            $scoreFactorId = $score->score_factor_id;
            // Use the pre-mapped score factors
//            $multiplier = $scoreFactorMap->get($scoreFactorId, 1); // default mulitplier to 1 if not found
            $multiplier = $this->scoreFactors->get($scoreFactorId, 1); // default mulitplier to 1 if not found

            $total += ($factorScore * $multiplier);
        }

        return $total;
    }
}
