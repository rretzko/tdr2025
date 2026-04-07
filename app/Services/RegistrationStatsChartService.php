<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Events\Versions\DailyRegistrationStat;
use App\Models\Students\VoicePart;

class RegistrationStatsChartService
{
    private ?int $liveRegisteredCandidates = null;
    private ?int $liveRegisteredSchools = null;
    private ?int $liveRegisteredVoicePartTotal = null;

    public function __construct(private readonly int $versionId)
    {
    }

    /**
     * Set live totals so chart titles match the email text stats.
     */
    public function setLiveTotals(int $registeredCandidates, int $registeredSchools, int $registeredVoicePartTotal): self
    {
        $this->liveRegisteredCandidates = $registeredCandidates;
        $this->liveRegisteredSchools = $registeredSchools;
        $this->liveRegisteredVoicePartTotal = $registeredVoicePartTotal;

        return $this;
    }

    /**
     * Return all chart data for the given version.
     */
    public function getChartData(): array
    {
        $stats = DailyRegistrationStat::where('version_id', $this->versionId)
            ->orderBy('snapshot_date')
            ->get();

        if ($stats->isEmpty()) {
            return [
                'hasData' => false,
                'labels' => [],
                'candidates' => [],
                'schools' => [],
                'voiceParts' => [],
            ];
        }

        $labels = $stats->pluck('snapshot_date')->map(fn ($d) => $d->format('M j'))->toArray();

        // Cumulative values come directly from the snapshot
        $cumulativeCandidates = $stats->pluck('registered_candidates')->toArray();
        $cumulativeSchools = $stats->pluck('registered_schools')->toArray();

        // Daily deltas: difference between consecutive cumulative values
        $dailyCandidates = [];
        $dailySchools = [];
        foreach ($cumulativeCandidates as $i => $val) {
            $dailyCandidates[] = $i === 0 ? $val : max(0, $val - $cumulativeCandidates[$i - 1]);
        }
        foreach ($cumulativeSchools as $i => $val) {
            $dailySchools[] = $i === 0 ? $val : max(0, $val - $cumulativeSchools[$i - 1]);
        }

        // Voice part data: collect all voice_part_ids across all snapshots
        $allVoicePartIds = collect();
        foreach ($stats as $stat) {
            if (is_array($stat->voice_part_counts)) {
                $allVoicePartIds = $allVoicePartIds->merge(array_keys($stat->voice_part_counts));
            }
        }
        $allVoicePartIds = $allVoicePartIds->unique()->sort()->values();

        // Get voice part labels
        $voicePartLabels = VoicePart::whereIn('id', $allVoicePartIds)
            ->orderBy('order_by')
            ->pluck('abbr', 'id')
            ->toArray();

        // Latest voice part counts for horizontal bar chart
        $latestStat = $stats->last();
        $latestVoicePartCounts = $latestStat->voice_part_counts ?? [];

        $voicePartData = [];
        foreach ($voicePartLabels as $vpId => $abbr) {
            $voicePartData[] = [
                'label' => $abbr,
                'count' => $latestVoicePartCounts[$vpId] ?? 0,
            ];
        }

        return [
            'hasData' => true,
            'labels' => $labels,
            'candidates' => [
                'daily' => $dailyCandidates,
                'cumulative' => $cumulativeCandidates,
            ],
            'schools' => [
                'daily' => $dailySchools,
                'cumulative' => $cumulativeSchools,
            ],
            'voiceParts' => $voicePartData,
        ];
    }

    /**
     * Build a QuickChart.io URL for the candidates chart (bar + line, dual Y-axis).
     */
    public function getCandidatesChartUrl(): string
    {
        $data = $this->getChartData();

        if (! $data['hasData']) {
            return '';
        }

        return $this->buildQuickChartUrl([
            'type' => 'bar',
            'data' => [
                'labels' => $data['labels'],
                'datasets' => [
                    [
                        'type' => 'bar',
                        'label' => 'Daily Registrations',
                        'data' => $data['candidates']['daily'],
                        'backgroundColor' => 'rgba(59,130,246,0.6)',
                        'yAxisID' => 'y-daily',
                    ],
                    [
                        'type' => 'line',
                        'label' => 'Cumulative',
                        'data' => $data['candidates']['cumulative'],
                        'borderColor' => 'rgba(220,38,38,1)',
                        'backgroundColor' => 'rgba(220,38,38,0.1)',
                        'fill' => false,
                        'yAxisID' => 'y-cumulative',
                    ],
                ],
            ],
            'options' => [
                'title' => ['display' => true, 'text' => 'Registered Candidates: ' . ($this->liveRegisteredCandidates ?? end($data['candidates']['cumulative']))],
                'scales' => [
                    'yAxes' => [
                        ['id' => 'y-daily', 'position' => 'left', 'scaleLabel' => ['display' => true, 'labelString' => 'Daily'], 'ticks' => ['beginAtZero' => true]],
                        ['id' => 'y-cumulative', 'position' => 'right', 'scaleLabel' => ['display' => true, 'labelString' => 'Cumulative'], 'ticks' => ['beginAtZero' => true], 'gridLines' => ['drawOnChartArea' => false]],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Build a QuickChart.io URL for the schools chart (bar + line, dual Y-axis).
     */
    public function getSchoolsChartUrl(): string
    {
        $data = $this->getChartData();

        if (! $data['hasData']) {
            return '';
        }

        return $this->buildQuickChartUrl([
            'type' => 'bar',
            'data' => [
                'labels' => $data['labels'],
                'datasets' => [
                    [
                        'type' => 'bar',
                        'label' => 'Daily Schools',
                        'data' => $data['schools']['daily'],
                        'backgroundColor' => 'rgba(16,185,129,0.6)',
                        'yAxisID' => 'y-daily',
                    ],
                    [
                        'type' => 'line',
                        'label' => 'Cumulative',
                        'data' => $data['schools']['cumulative'],
                        'borderColor' => 'rgba(220,38,38,1)',
                        'backgroundColor' => 'rgba(220,38,38,0.1)',
                        'fill' => false,
                        'yAxisID' => 'y-cumulative',
                    ],
                ],
            ],
            'options' => [
                'title' => ['display' => true, 'text' => ($this->liveRegisteredSchools ?? end($data['schools']['cumulative'])) . ' Schools with Registered Candidates'],
                'scales' => [
                    'yAxes' => [
                        ['id' => 'y-daily', 'position' => 'left', 'scaleLabel' => ['display' => true, 'labelString' => 'Daily'], 'ticks' => ['beginAtZero' => true]],
                        ['id' => 'y-cumulative', 'position' => 'right', 'scaleLabel' => ['display' => true, 'labelString' => 'Cumulative'], 'ticks' => ['beginAtZero' => true], 'gridLines' => ['drawOnChartArea' => false]],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Build a QuickChart.io URL for the voice parts horizontal bar chart.
     */
    public function getVoicePartsChartUrl(): string
    {
        $data = $this->getChartData();

        if (! $data['hasData'] || empty($data['voiceParts'])) {
            return '';
        }

        $labels = array_column($data['voiceParts'], 'label');
        $counts = array_column($data['voiceParts'], 'count');

        return $this->buildQuickChartUrl([
            'type' => 'horizontalBar',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Registered Candidates',
                        'data' => $counts,
                        'backgroundColor' => [
                            'rgba(59,130,246,0.6)',
                            'rgba(16,185,129,0.6)',
                            'rgba(245,158,11,0.6)',
                            'rgba(220,38,38,0.6)',
                            'rgba(139,92,246,0.6)',
                            'rgba(236,72,153,0.6)',
                            'rgba(20,184,166,0.6)',
                            'rgba(249,115,22,0.6)',
                        ],
                    ],
                ],
            ],
            'options' => [
                'title' => ['display' => true, 'text' => ($this->liveRegisteredVoicePartTotal ?? array_sum($counts)) . ' Registrations by Voice Part'],
                'scales' => [
                    'xAxes' => [['scaleLabel' => ['display' => true, 'labelString' => 'Count'], 'ticks' => ['beginAtZero' => true]]],
                ],
                'legend' => ['display' => false],
            ],
        ]);
    }

    private function buildQuickChartUrl(array $config): string
    {
        $json = json_encode($config, JSON_UNESCAPED_SLASHES);

        return 'https://quickchart.io/chart?c=' . urlencode($json) . '&w=600&h=300&bkg=white&v=2.9.4';
    }
}
