<?php

namespace App\Services;

use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Students\VoicePart;

class UpdateAuditionResultsService
{
    private int $candidateId;
    private int $scoreCount;
    private int $total;
    private VoicePart $voicePart;

    public function __construct(
        private readonly Candidate $candidate,
        private readonly bool $accepted = false,
        private readonly string $acceptance_abbr = 'tbd',
    ) {
        $this->candidateId = $this->candidate->id;
        $this->scoreCount = $this->getScoreCount();
        $this->total = $this->getTotal();
        $this->voicePart = VoicePart::find($this->candidate->voice_part_id);

        $this->init();
    }

    private function getScoreCount(): int
    {
        return Score::query()
            ->where('candidate_id', $this->candidateId)
            ->count('id') ?? 0;
    }

    private function getTotal(): int
    {
        $service = new CalcTotalScoreService($this->candidate);
        return $service->totalScore();
    }

    private function init(): void
    {
        AuditionResult::updateOrCreate(
            [
                'candidate_id' => $this->candidateId,
                'version_id' => $this->candidate->version_id,
                'voice_part_id' => $this->voicePart->id,
                'school_id' => $this->candidate->school_id,
                'voice_part_order_by' => $this->voicePart->order_by,

            ],
            [
                'score_count' => $this->scoreCount,
                'total' => $this->total,
                'accepted' => $this->accepted,
                'acceptance_abbr' => $this->acceptance_abbr,
            ]
        );
    }
}
