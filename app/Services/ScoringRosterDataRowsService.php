<?php

namespace App\Services;

use App\Models\Events\EventEnsemble;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ScoringRosterDataRowsService
{
    private array $rows = [];
    private array $scores = [];

    public function __construct(
        private readonly int    $versionId,
        private readonly array  $voicePartIds,
        private readonly int    $eventEnsembleId = 0,
        private readonly int    $judgeCount,
        private readonly bool   $scoresAscending,
        private readonly int    $scoreFactorCount,
        private readonly int    $voicePartId,
        private readonly string $ensembleAbbr = '',
    )
    {
        $this->init();
    }

    private function init(): void
    {
        $candidateIds = $this->getCandidateIds();
        $this->setRows($candidateIds);
        $this->setScores();
    }

    private function addAuditionResultsToBuilder(Builder $query): Builder
    {
        if (!$this->ensembleAbbr) {
            return $query;
        }

        return $query->leftJoin('audition_results', 'audition_results.candidate_id', '=', 'candidates.id')
            ->where('audition_results.acceptance_abbr', 'LIKE', $this->ensembleAbbr);
    }

    private function addVoicePartIdToBuilder(Builder $query): Builder
    {
        if (!$this->voicePartId) {
            return $query;
        }

        return $query->where('candidates.voice_part_id', $this->voicePartId);
    }

    private function getCandidateIds(): array
    {
        $query = DB::table('candidates')
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered');

        $query = $this->addVoicePartIdToBuilder($query);

        $query = $this->addAuditionResultsToBuilder($query);

        return $query->pluck('candidates.id')
            ->toArray();

    }

    private function setRows(array $candidateIds): void
    {
        $this->rows = DB::table('candidates')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->leftJoin('audition_results', 'audition_results.candidate_id', '=', 'candidates.id')
            ->whereIn('candidates.id', $candidateIds)
            ->distinct()
            ->select(
                'candidates.id',
                'candidates.program_name AS programName',
                'schools.name AS schoolName',
                'voice_parts.abbr AS voicePartAbbr',
                'voice_parts.order_by AS voicePartOrderBy',
                'audition_results.total',
                'audition_results.accepted',
                'audition_results.acceptance_abbr',
                'audition_results.total AS total_score'
            )
            ->orderBy('voicePartOrderBy')
            ->orderBy('audition_results.total', ($this->scoresAscending ? 'asc' : 'desc'))
            ->orderBy('candidates.id')
            ->get()
            ->toArray();
    }
//    private function oldSetRows(): void
//    {
//        $versionConfigAdjudication = VersionConfigAdjudication::where('version_id', $this->versionId)->first();
//        $scoresAscending = $versionConfigAdjudication->scores_ascending;
//        $judgeCount = $versionConfigAdjudication->judge_per_room_count;
//        $ensembleAbbr = ($this->eventEnsembleId)
//            ? EventEnsemble::find($this->eventEnsembleId)->abbr
//            : '%%';
//
//        $candidates = DB::table('candidates')
//            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
//            ->join('schools', 'schools.id', '=', 'candidates.school_id')
//            ->leftJoin('audition_results', 'audition_results.candidate_id', '=', 'candidates.id')
//            ->where('candidates.version_id', $this->versionId)
//            ->where('candidates.status', 'registered')
//            ->whereIn('candidates.voice_part_id', $this->voicePartIds)
//            ->where('audition_results.acceptance_abbr', 'LIKE', $ensembleAbbr)
//            ->distinct()
//            ->select('candidates.id', 'candidates.program_name AS programName',
//                'schools.name AS schoolName',
//                'voice_parts.abbr AS voicePartAbbr', 'voice_parts.order_by AS voicePartOrderBy',
//                'audition_results.total', 'audition_results.accepted', 'audition_results.acceptance_abbr',
//            )
//            ->orderBy('voicePartOrderBy')
//            ->orderBy('audition_results.total', ($scoresAscending ? 'asc' : 'desc'))
//            ->orderBy('candidates.id')
//            ->get()
//            ->toArray();
//
//        $this->getScores($candidates, $judgeCount);
////dd($candidates);
//        $this->rows = $candidates;
//    }

    private function setScores(): void
    {
        $this->scores = DB::table('scores')
            ->where('version_id', $this->versionId)
            ->distinct()
            ->select('candidate_id', 'score', 'judge_order_by', 'score_factor_order_by')
            ->whereBetween('judge_order_by', [1, $this->judgeCount])
            ->whereBetween('score_factor_order_by', [1, $this->scoreFactorCount])
            ->orderBy('judge_order_by')
            ->orderBy('score_factor_order_by')
            ->get()
            ->groupBy('candidate_id')
            ->map(function ($items) {
                // Create a default 3x10 matrix filled with 0
                $defaultMatrix = [];
                for ($j = 1; $j <= $this->judgeCount; $j++) {
                    for ($f = 1; $f <= $this->scoreFactorCount; $f++) {
                        $defaultMatrix["{$j}-{$f}"] = 0;
                    }
                }

                // Replace defaults with actual scores
                foreach ($items as $item) {
                    $key = "{$item->judge_order_by}-{$item->score_factor_order_by}";
                    $defaultMatrix[$key] = $item->score;
                }

                return array_values($defaultMatrix); // Convert associative array to indexed array
            })
            ->toArray();

    }

//    private function oldGetScores(array &$candidates, int $judgeCount): void
//    {
//        $version = Version::find($this->versionId);
//        $factors = $version->scoreFactors;
//
//        foreach ($candidates as $candidate) {
//
//            for ($i = 1; $i <= $judgeCount; $i++) {
//
//                foreach ($factors as $factor) {
//
//                    $candidate->scores[] = Score::query()
//                        ->where('candidate_id', $candidate->id)
//                        ->where('judge_order_by', $i)
//                        ->where('score_factor_order_by', $factor->order_by)
//                        ->select('score')
//                        ->value('score') ?? 0;
//                }
//            }
//        }
//    }

    public function getRows(): array
    {
        return $this->rows;
    }

    public function getRowsScores(): array
    {
        return $this->scores;
    }

}
