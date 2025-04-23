<?php

namespace App\Services\Sandbox;

use App\Models\Events\Versions\Participations\Candidate;
use Illuminate\Support\Facades\DB;

class GenerateScoreResultsService
{
    private int $counter = 0;

    public function __construct(private readonly int $versionId)
    {
        $this->init();
    }

    private function init(): void
    {
        $registrants = $this->setRegistrants();
        dd($registrants);
    }

    private function setRegistrants(): array
    {
        return Candidate::query()
            ->join('voice_parts', 'candidates.voice_part_id', '=', 'voice_parts.id')
            ->leftJoin('scores', function ($join) {
                $join->on('candidates.id', '=', 'scores.candidate_id')
                    ->where('scores.version_id', $this->versionId);
            })
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->select('candidates.id', 'candidates.voice_part_id', 'candidates.school_id',
                'voice_parts.order_by as voicePartOrderBy',
                \DB::raw('COUNT(scores.id) as score_count')
            )
            ->groupBy('candidates.id', 'candidates.voice_part_id', 'candidates.school_id', 'voice_parts.order_by')
            ->get()
            ->toArray();
    }

    public function getCounter(): int
    {
        return $this->counter;
    }
}
