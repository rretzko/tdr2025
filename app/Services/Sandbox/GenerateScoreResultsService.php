<?php

namespace App\Services\Sandbox;

use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Participations\Candidate;
use Illuminate\Support\Facades\DB;

class GenerateScoreResultsService
{
    private int $counter = 0;

    public function __construct(private readonly int $versionId)
    {
        $this->init();
    }

    public function getCounter(): int
    {
        return $this->counter;
    }

    private function init(): void
    {
        $registrants = $this->setRegistrants();

        $results = $this->setAuditionResults($registrants);

        $this->counter = $this->batchInsertAuditionResults($results);
    }

    private function batchInsertAuditionResults(array $results): int
    {
        //insert $data if count is multiple of 100 to manage memory
        $counter = 0;

        DB::transaction(function () use ($results, &$counter) {
            foreach ($results as $result) {
                AuditionResult::insert($result);
                $counter++;
            }
        });

        return $counter;
    }

    private function setAuditionResults(array $registrants): array
    {
        $results = [];

        //limit action to local dev environment
        if (app('env') === 'local') {
            //clear current results if any
            DB::table('audition_results')
                ->where('version_id', $this->versionId)
                ->delete();

            foreach ($registrants as $registrant) {

                $results[] = [
                    'candidate_id' => $registrant['id'],
                    'version_id' => $this->versionId,
                    'voice_part_id' => $registrant['voice_part_id'],
                    'school_id' => $registrant['school_id'],
                    'voice_part_order_by' => $registrant['voicePartOrderBy'],
                    'score_count' => $registrant['score_count'],
                    'total' => $registrant['total'],
                    'accepted' => 0,
                    'acceptance_abbr' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        return $results;
    }

    /**
     * Refactor algorithm to replace \DB::raw('SUM(scores.score) as total')
     * with a variable scoring algorithm
     * @return array
     * @todo develop scoring algorithm to account for versions which do not employ simple sum(scores) scoring method
     */
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
                \DB::raw('COUNT(scores.id) as score_count'),
                \DB::raw('SUM(scores.score) as total')
            )
            ->groupBy('candidates.id', 'candidates.voice_part_id', 'candidates.school_id', 'voice_parts.order_by')
            ->get()
            ->toArray();
    }
}
