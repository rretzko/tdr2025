<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class VersionSummaryReportService
{
    private const REGION_I   = ['Bergen', 'Essex', 'Hudson', 'Morris', 'Passaic', 'Sussex', 'Warren'];
    private const REGION_II  = ['Hunterdon', 'Mercer', 'Middlesex', 'Monmouth', 'Somerset', 'Union'];
    private const REGION_III = ['Camden', 'Gloucester', 'Cumberland', 'Salem', 'Atlantic', 'Cape May', 'Ocean', 'Burlington'];

    // Lower score = better (best: 10 factors × 1 × 3 judges = 30; worst: 10 × 9 × 3 = 270)
    private const SCORE_MIN = 30;
    private const SCORE_MAX = 270;

    // Tolerance per scoring category (from rooms config)
    private const CATEGORY_TOLERANCES = [1 => 5, 2 => 5, 3 => 8];

    // Flag threshold: judges whose avg deviates from the overall avg by more than this
    private const JUDGE_FLAG_THRESHOLD = 0.5;

    // Within-room outlier threshold
    private const WITHIN_ROOM_THRESHOLD = 0.3;

    public function __construct(private readonly int $versionId) {}

    public function getData(): array
    {
        return [
            'version'      => $this->getVersionInfo(),
            'registration' => $this->getRegistrationData(),
            'adjudication' => $this->getAdjudicationData(),
            'scoring'      => $this->getScoringData(),
            'fairness'     => $this->getFairnessData(),
            'anomalies'    => $this->getAnomalies(),
        ];
    }

    private function getVersionInfo(): array
    {
        $row = DB::table('versions as v')
            ->join('events as e', 'v.event_id', '=', 'e.id')
            ->where('v.id', $this->versionId)
            ->select('v.name', 'v.short_name', 'v.status', 'e.name as event_name')
            ->first();

        return $row ? (array) $row : [];
    }

    private function getRegistrationData(): array
    {
        $rows = DB::table('candidates as c')
            ->join('schools as s', 'c.school_id', '=', 's.id')
            ->join('counties as co', 's.county_id', '=', 'co.id')
            ->join('voice_parts as vp', 'c.voice_part_id', '=', 'vp.id')
            ->where('c.version_id', $this->versionId)
            ->where('c.status', 'registered')
            ->select('co.name as county', 'vp.descr as voice_part', 'vp.order_by', 'c.school_id')
            ->get();

        $total = $rows->count();

        $byVoicePart = $rows->groupBy('voice_part')
            ->map(fn($g, $name) => [
                'name'     => $name,
                'order_by' => $g->first()->order_by,
                'count'    => $g->count(),
                'pct'      => $total > 0 ? round($g->count() / $total * 100, 1) : 0,
            ])
            ->sortBy('order_by')
            ->values()
            ->toArray();

        $byRegion = $this->buildRegionalCounts($rows, 'county', $total);

        // School counts
        $registeredSchools = $rows->unique('school_id')->count();
        $schoolsWithAcceptance = DB::table('audition_results')
            ->where('version_id', $this->versionId)
            ->where('accepted', 1)
            ->distinct('school_id')
            ->count('school_id');

        return [
            'total'                   => $total,
            'schools_count'           => $registeredSchools,
            'schools_with_acceptance' => $schoolsWithAcceptance,
            'schools_zero_acceptance' => $registeredSchools - $schoolsWithAcceptance,
            'by_voice_part'           => $byVoicePart,
            'by_region'               => $byRegion,
        ];
    }

    private function getAdjudicationData(): array
    {
        $results = DB::table('audition_results as ar')
            ->join('voice_parts as vp', 'ar.voice_part_id', '=', 'vp.id')
            ->join('schools as s', 'ar.school_id', '=', 's.id')
            ->join('counties as co', 's.county_id', '=', 'co.id')
            ->where('ar.version_id', $this->versionId)
            ->select(
                'ar.total', 'ar.accepted', 'ar.acceptance_abbr',
                'vp.descr as voice_part', 'vp.order_by',
                'co.name as county'
            )
            ->get();

        $total    = $results->count();
        $accepted = $results->where('accepted', 1)->count();

        // Ensemble breakdown from event_ensembles
        $ensembles = DB::table('event_ensembles as ee')
            ->join('versions as v', 'v.event_id', '=', 'ee.event_id')
            ->where('v.id', $this->versionId)
            ->whereNull('ee.deleted_at')
            ->select('ee.abbr', 'ee.ensemble_short_name')
            ->get()
            ->keyBy('abbr');

        $ensembleBreakdown = $results->where('accepted', 1)
            ->groupBy('acceptance_abbr')
            ->map(function ($g, $abbr) use ($ensembles) {
                $label = isset($ensembles[$abbr])
                    ? $ensembles[$abbr]->ensemble_short_name
                    : ucfirst($abbr);
                return ['abbr' => $abbr, 'label' => $label, 'count' => $g->count()];
            })
            ->sortByDesc('count')
            ->values()
            ->toArray();

        $byVoicePart  = $this->buildVoicePartStats($results);
        $byRegion     = $this->buildRegionalAdjudication($results);

        return [
            'total'         => $total,
            'accepted'      => $accepted,
            'not_accepted'  => $total - $accepted,
            'rate'          => $total > 0 ? round($accepted / $total * 100, 1) : 0,
            'score_min'     => self::SCORE_MIN,
            'score_max'     => self::SCORE_MAX,
            'ensembles'     => $ensembleBreakdown,
            'by_voice_part' => $byVoicePart,
            'by_region'     => $byRegion,
        ];
    }

    private function buildVoicePartStats(Collection $results): array
    {
        return $results->groupBy('voice_part')
            ->map(function ($g, $vp) {
                $accepted = $g->where('accepted', 1);
                // Exclude administrative exclusions from rejected pool for cutoff analysis
                $rejected  = $g->where('accepted', 0)->where('acceptance_abbr', '!=', 'unsigned');
                $unsigned  = $g->where('acceptance_abbr', 'unsigned');

                $accCnt = $accepted->count();
                $rejCnt = $rejected->count();

                $lastIn   = $accCnt > 0 ? $accepted->max('total') : null;
                $firstOut = $rejCnt > 0 ? $rejected->min('total') : null;

                return [
                    'name'           => $vp,
                    'order_by'       => $g->first()->order_by,
                    'auditioned'     => $g->count(),
                    'accepted'       => $accCnt,
                    'not_accepted'   => $rejCnt,
                    'unsigned'       => $unsigned->count(),
                    'rate'           => $g->count() > 0 ? round($accCnt / $g->count() * 100, 1) : 0,
                    'avg_accepted'   => $accCnt > 0 ? round($accepted->avg('total'), 1) : null,
                    'avg_rejected'   => $rejCnt > 0 ? round($rejected->avg('total'), 1) : null,
                    'cutoff_last_in' => $lastIn,
                    'cutoff_first_out' => $firstOut,
                    'cutoff_gap'     => ($lastIn && $firstOut) ? ($firstOut - $lastIn) : null,
                ];
            })
            ->sortBy('order_by')
            ->values()
            ->toArray();
    }

    private function getScoringData(): array
    {
        $catAvgs = DB::table('scores as sc')
            ->join('score_categories as cat', 'sc.score_category_id', '=', 'cat.id')
            ->where('sc.version_id', $this->versionId)
            ->groupBy('sc.score_category_id', 'cat.descr', 'cat.order_by')
            ->select(
                'sc.score_category_id as id',
                'cat.descr as name',
                'cat.order_by',
                DB::raw('AVG(sc.score) as avg_score'),
                DB::raw('COUNT(DISTINCT sc.score_factor_id) as factor_count')
            )
            ->orderBy('cat.order_by')
            ->get();

        $categories = $catAvgs->map(fn($c) => [
            'id'           => $c->id,
            'name'         => ucfirst($c->name),
            'factor_count' => (int) $c->factor_count,
            'avg_score'    => round((float) $c->avg_score, 2),
            'tolerance'    => self::CATEGORY_TOLERANCES[$c->id] ?? 5,
        ])->values()->toArray();

        return [
            'categories'    => $categories,
            'total_factors' => array_sum(array_column($categories, 'factor_count')),
        ];
    }

    private function getFairnessData(): array
    {
        // Tolerance violation check: per candidate+factor, is the spread > allowed tolerance?
        $spreads = DB::table('scores')
            ->where('version_id', $this->versionId)
            ->groupBy('candidate_id', 'score_category_id', 'score_factor_id')
            ->select('score_category_id', DB::raw('MAX(score) - MIN(score) as spread'))
            ->get();

        $totalEvals = $spreads->count();
        $violations = $spreads->filter(function ($r) {
            return $r->spread > (self::CATEGORY_TOLERANCES[$r->score_category_id] ?? 5);
        })->count();

        $overallAvg = (float) DB::table('scores')->where('version_id', $this->versionId)->avg('score');

        // Per-judge stats with room name and role
        $judgeRows = DB::table('scores as sc')
            ->join('judges as j', 'sc.judge_id', '=', 'j.id')
            ->join('rooms as r', 'j.room_id', '=', 'r.id')
            ->join('users as u', 'j.user_id', '=', 'u.id')
            ->where('sc.version_id', $this->versionId)
            ->groupBy('sc.judge_id', 'r.room_name', 'j.judge_type', 'u.first_name', 'u.last_name')
            ->select(
                'sc.judge_id',
                'r.room_name',
                'j.judge_type',
                DB::raw('CONCAT(u.first_name, " ", u.last_name) as judge_name'),
                DB::raw('AVG(sc.score) as avg_score'),
                DB::raw('STDDEV(sc.score) as std_dev'),
                DB::raw('COUNT(*) as score_count')
            )
            ->get()
            ->map(fn($j) => [
                'judge_id'   => $j->judge_id,
                'room'       => $j->room_name,
                'role'       => $j->judge_type,
                'name'       => $j->judge_name,
                'avg'        => round((float) $j->avg_score, 3),
                'std_dev'    => round((float) $j->std_dev, 3),
                'count'      => (int) $j->score_count,
                'diff'       => round((float) $j->avg_score - $overallAvg, 3),
                'flagged'    => abs((float) $j->avg_score - $overallAvg) > self::JUDGE_FLAG_THRESHOLD,
            ]);

        // Within-room comparison: flag judges whose avg differs from their room peers by > threshold
        $withinRoomFlags = $judgeRows->groupBy('room')
            ->map(function ($judges, $room) {
                if ($judges->count() < 2) {
                    return null;
                }
                $roomAvg  = $judges->avg('avg');
                $outliers = $judges
                    ->filter(fn($j) => abs($j['avg'] - $roomAvg) > self::WITHIN_ROOM_THRESHOLD)
                    ->map(fn($j) => array_merge($j, [
                        'room_diff' => round($j['avg'] - $roomAvg, 3),
                    ]));

                return $outliers->isNotEmpty() ? [
                    'room'     => $room,
                    'room_avg' => round($roomAvg, 3),
                    'outliers' => $outliers->values()->toArray(),
                ] : null;
            })
            ->filter()
            ->values()
            ->toArray();

        // Category-level averages to show Scales vs. Solo vs. Quintet scoring patterns
        $categoryAvgs = DB::table('scores as sc')
            ->join('score_categories as cat', 'sc.score_category_id', '=', 'cat.id')
            ->where('sc.version_id', $this->versionId)
            ->groupBy('sc.score_category_id', 'cat.descr', 'cat.order_by')
            ->select('cat.descr as name', 'cat.order_by', DB::raw('AVG(sc.score) as avg_score'))
            ->orderBy('cat.order_by')
            ->get()
            ->map(fn($r) => [
                'name' => ucfirst($r->name),
                'avg'  => round((float) $r->avg_score, 3),
                'diff' => round((float) $r->avg_score - $overallAvg, 3),
            ])->values()->toArray();

        $flaggedJudges = $judgeRows->where('flagged', true)
            ->sortBy('diff')
            ->values()
            ->toArray();

        return [
            'total_evaluations' => $totalEvals,
            'violations'        => $violations,
            'judge_count'       => $judgeRows->count(),
            'overall_avg'       => round($overallAvg, 3),
            'category_avgs'     => $categoryAvgs,
            'flagged_judges'    => $flaggedJudges,
            'within_room_flags' => $withinRoomFlags,
        ];
    }

    private function getAnomalies(): array
    {
        $anomalies = [];

        $results = DB::table('audition_results as ar')
            ->join('voice_parts as vp', 'ar.voice_part_id', '=', 'vp.id')
            ->where('ar.version_id', $this->versionId)
            ->select('vp.descr', 'vp.order_by', 'ar.total', 'ar.accepted', 'ar.acceptance_abbr')
            ->orderBy('vp.order_by')
            ->get();

        foreach ($results->groupBy('descr') as $vp => $group) {
            // Administrative exclusions
            foreach ($group->where('acceptance_abbr', 'unsigned') as $u) {
                $rank  = $group->where('total', '<=', $u->total)->count();
                $total = $group->count();
                $anomalies[] = [
                    'type'     => 'administrative_exclusion',
                    'severity' => 'note',
                    'voice_part' => $vp,
                    'title'    => "{$vp}: Candidate excluded for administrative reasons",
                    'detail'   => "One {$vp} candidate (score: {$u->total}, "
                        . "which would have ranked approximately {$rank} of {$total}) "
                        . "did not complete a required acknowledgment and was marked \"unsigned.\" "
                        . "Their score would have placed them within the accepted pool.",
                ];
            }

            // Non-monotonic cutoff: best rejected scores better (lower) than worst accepted
            $accepted = $group->where('accepted', 1);
            $rejected = $group->where('accepted', 0)->where('acceptance_abbr', '!=', 'unsigned');

            if ($accepted->isNotEmpty() && $rejected->isNotEmpty()) {
                $worstAccepted = $accepted->max('total');
                $bestRejected  = $rejected->min('total');

                if ($bestRejected < $worstAccepted) {
                    $anomalies[] = [
                        'type'       => 'cutoff_overlap',
                        'severity'   => 'warning',
                        'voice_part' => $vp,
                        'title'      => "{$vp}: Score cutoff overlap detected",
                        'detail'     => "The highest-scoring rejected {$vp} candidate "
                            . "(score: {$bestRejected}) outperformed the lowest-scoring accepted "
                            . "candidate (score: {$worstAccepted}). This may reflect a manual "
                            . "cutoff override or an unusual tie-breaking decision.",
                    ];
                }
            }
        }

        return $anomalies;
    }

    // --- Regional helpers ---

    private function buildRegionalCounts(Collection $rows, string $countyField, int $total): array
    {
        return $this->eachRegion(fn($counties, $label) => [
            'label'    => $label,
            'count'    => $rows->whereIn($countyField, $counties)->count(),
            'pct'      => $total > 0
                ? round($rows->whereIn($countyField, $counties)->count() / $total * 100, 1)
                : 0,
        ]);
    }

    private function buildRegionalAdjudication(Collection $results): array
    {
        return $this->eachRegion(function ($counties, $label) use ($results) {
            $group = $results->whereIn('county', $counties);
            $cnt   = $group->count();
            $acc   = $group->where('accepted', 1)->count();
            return [
                'label'      => $label,
                'auditioned' => $cnt,
                'accepted'   => $acc,
                'rate'       => $cnt > 0 ? round($acc / $cnt * 100, 1) : 0,
            ];
        });
    }

    private function eachRegion(callable $fn): array
    {
        return [
            $fn(self::REGION_I,   'Region I — North NJ'),
            $fn(self::REGION_II,  'Region II — Central NJ'),
            $fn(self::REGION_III, 'Region III — South NJ'),
        ];
    }
}
