<?php

namespace App\Services;

use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use Illuminate\Support\Facades\DB;

class ScoringRosterDataRowsService
{
    private array $rows = [];

    public function __construct(private int $versionId, private int $voicePartId)
    {
        $this->init();
    }

    private function init(): void
    {
        $this->setRows();
    }

    private function setRows(): void
    {
        $versionConfigAdjudication = VersionConfigAdjudication::where('version_id', $this->versionId)->first();
        $scoresAscending = $versionConfigAdjudication->scores_ascending;
        $judgeCount = $versionConfigAdjudication->judge_per_room_count;

        $candidates = DB::table('candidates')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->leftJoin('audition_results', 'audition_results.candidate_id', '=', 'candidates.id')
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->where('candidates.voice_part_id', $this->voicePartId)
            ->distinct()
            ->select('candidates.id',
                'voice_parts.abbr AS voicePartAbbr', 'voice_parts.order_by AS voicePartOrderBy',
                'audition_results.total', 'audition_results.accepted', 'audition_results.acceptance_abbr',
            )
            ->orderBy('voicePartOrderBy')
            ->orderBy('audition_results.total', ($scoresAscending ? 'asc' : 'desc'))
            ->orderBy('candidates.id')
            ->get()
            ->toArray();

        $this->getScores($candidates, $judgeCount);
//dd($candidates);
        $this->rows = $candidates;
    }

    private function getScores(array &$candidates, int $judgeCount): void
    {
        $version = Version::find($this->versionId);
        $factors = $version->scoreFactors;

        foreach ($candidates as $candidate) {

            for ($i = 1; $i <= $judgeCount; $i++) {

                foreach ($factors as $factor) {

                    $candidate->scores[] = Score::query()
                        ->where('candidate_id', $candidate->id)
                        ->where('judge_order_by', $i)
                        ->where('score_factor_order_by', $factor->order_by)
                        ->select('score')
                        ->value('score') ?? 0;
                }
            }


        }
    }

    public function getRows(): array
    {
        return $this->rows;
    }

}
