<?php

namespace App\Services;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScoreSeederService
{
    private const CHUNK_SIZE = 100;

    public function __construct(
        private readonly Version $version
    ) {}

    public function seedScores(bool $calculateResults = true): array
    {
        $resultsSummary = [];
        $scoreFactors = $this->version->scoreFactors;

        Log::info('Starting seed scores', [
            'version_id' => $this->version->id,
            'score_factors_count' => $scoreFactors->count(),
        ]);

        if ($scoreFactors->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No score factors found'
            ];
        }

        $totalProcessed = 0;
        $totalInserted = 0;

        // Use chunk to avoid memory issues
        $this->version->versionRegistrants()
            ->chunk(self::CHUNK_SIZE, function ($registrants) use ($scoreFactors, &$totalProcessed, &$totalInserted) {
                $inserted = $this->processRegistrantChunk($registrants, $scoreFactors);
                $totalProcessed += $registrants->count();
                $totalInserted += $inserted;

                Log::info('Chunk processed', [
                    'registrants_in_chunk' => $registrants->count(),
                    'inserted_in_chunk' => $inserted,
                    'total_inserted_so_far' => $totalInserted
                ]);
            });

        Log::info('Seeding complete', [
            'total_processed' => $totalProcessed,
            'total_inserted' => $totalInserted,
            'calculate_results' => $calculateResults
        ]);

        // Calculate audition results after seeding scores
        if ($calculateResults && $totalInserted > 0) {
            Log::info('Calculating audition results...');
            $auditionResultService = new AuditionResultService($this->version);
            $resultsSummary = $auditionResultService->calculateResults();
            Log::info('Audition results calculated', $resultsSummary);
        } else {
            Log::warning('Skipping audition results calculation', [
                'calculate_results' => $calculateResults,
                'total_inserted' => $totalInserted
            ]);
        }

        return [
            'success' => true,
            'processed' => $totalProcessed,
            'inserted' => $totalInserted,
            'results' => $resultsSummary
        ];
    }

    private function processRegistrantChunk($registrants, $scoreFactors): int
    {
        $scoresToInsert = [];
        $now = now();

        foreach ($registrants as $registrant) {
            // Get the room for this registrant's voice part
            $room = $this->getRoomForVoicePart($registrant->voice_part_id);

            if (!$room) {
                Log::warning('No room found for registrant', [
                    'candidate_id' => $registrant->id,
                    'voice_part_id' => $registrant->voice_part_id
                ]);
                continue;
            }

            // Get judges for this specific room (excluding monitors)
            $judges = $this->getJudgesForRoom($room);

            if ($judges->isEmpty()) {
                Log::warning('No judges found for room', [
                    'room_id' => $room->id,
                    'candidate_id' => $registrant->id
                ]);
                continue;
            }

            foreach ($judges as $judge) {
                foreach ($scoreFactors as $scoreFactor) {
                    $randScore = rand($scoreFactor->best, $scoreFactor->worst);

                    $scoresToInsert[] = [
                        'version_id' => $this->version->id,
                        'candidate_id' => $registrant->id,
                        'student_id' => $registrant->student_id,
                        'school_id' => $registrant->school_id,
                        'judge_id' => $judge->id,
                        'judge_order_by' => $judge->order_by ?? 0,
                        'score_factor_id' => $scoreFactor->id,
                        'score_factor_order_by' => $scoreFactor->order_by,
                        'score_category_id' => $scoreFactor->score_category_id,
                        'score_category_order_by' => $scoreFactor->scoreCategory->order_by,
                        'voice_part_id' => $registrant->voice_part_id,
                        'voice_part_order_by' => $registrant->voicePart->order_by,
                        'score' => $randScore,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        if (!empty($scoresToInsert)) {
            // Insert in batches for better performance
            foreach (array_chunk($scoresToInsert, 500) as $batch) {
                DB::table('scores')->insert($batch);
            }
        }

        return count($scoresToInsert);
    }

    /**
     * Get the room that handles a specific voice part
     */
    private function getRoomForVoicePart(int $voicePartId): ?Room
    {
        return Room::where('version_id', $this->version->id)
            ->whereHas('roomVoiceParts', function ($query) use ($voicePartId) {
                $query->where('voice_part_id', $voicePartId);
            })
            ->first();
    }

    /**
     * Get judges for a specific room (excluding monitors)
     */
    private function getJudgesForRoom(Room $room)
    {
        $excludes = ['monitor'];

        return $room->judges()
            ->whereNotIn('judge_type', $excludes)
            ->get();
    }
}
