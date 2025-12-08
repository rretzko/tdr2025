<?php

namespace App\Services;

use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Version;
use Illuminate\Support\Facades\DB;

class AuditionResultService
{
    private const CHUNK_SIZE = 100;

    public function __construct(
        private readonly Version $version
    ) {}

    public function calculateResults(): array
    {
        $totalProcessed = 0;
        $totalUpdated = 0;

        // Process registrants in chunks
        $this->version->versionRegistrants()
            ->chunk(self::CHUNK_SIZE, function ($registrants) use (&$totalProcessed, &$totalUpdated) {
                $updated = $this->processResultsChunk($registrants);
                $totalProcessed += $registrants->count();
                $totalUpdated += $updated;
            });

        return [
            'success' => true,
            'processed' => $totalProcessed,
            'updated' => $totalUpdated
        ];
    }

    private function processResultsChunk($registrants): int
    {
        $resultsToUpsert = [];
        $now = now();

        foreach ($registrants as $registrant) {
            $scoreData = $this->getScoreData($registrant->id);

            $resultsToUpsert[] = [
                'candidate_id' => $registrant->id,
                'version_id' => $this->version->id,
                'voice_part_id' => $registrant->voice_part_id,
                'school_id' => $registrant->school_id,
                'voice_part_order_by' => $registrant->voicePart->order_by ?? 0,
                'score_count' => $scoreData['count'],
                'total' => $scoreData['total'],
                'accepted' => 0,
                'acceptance_abbr' => 'na',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($resultsToUpsert)) {
            // Use upsert to handle updates and inserts efficiently
            AuditionResult::upsert(
                $resultsToUpsert,
                ['candidate_id', 'version_id'], // unique keys
                ['score_count', 'total', 'voice_part_id', 'school_id', 'voice_part_order_by', 'updated_at'] // columns to update
            );
        }

        return count($resultsToUpsert);
    }

    private function getScoreData(int $candidateId): array
    {
        return Score::where('candidate_id', $candidateId)
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(score), 0) as total')
            ->first()
            ->toArray();
    }

    /**
     * Recalculate results for a specific candidate
     * Useful when individual scores are updated
     */
    public function recalculateForCandidate(Candidate $candidate): void
    {
        $scoreData = $this->getScoreData($candidate->id);

        AuditionResult::updateOrCreate(
            [
                'candidate_id' => $candidate->id,
                'version_id' => $this->version->id,
            ],
            [
                'voice_part_id' => $candidate->voice_part_id,
                'school_id' => $candidate->school_id,
                'voice_part_order_by' => $candidate->voicePart->order_by ?? 0,
                'score_count' => $scoreData['count'],
                'total' => $scoreData['total'],
                'accepted' => 0,
                'acceptance_abbr' => 'na',
            ]
        );
    }
}
