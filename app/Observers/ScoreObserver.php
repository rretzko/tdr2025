<?php

namespace App\Observers;

use App\Models\Events\Versions\Scoring\Score;
use App\Services\AuditionResultService;
use Illuminate\Support\Facades\Log;

class ScoreObserver
{
    public function updated(Score $score): void
    {
        if ($score->isDirty('score')) {
            $this->updateAuditionResult($score);
        }
    }

    public function created(Score $score): void
    {
        $this->updateAuditionResult($score);
    }

    public function deleted(Score $score): void
    {
        $this->updateAuditionResult($score);
    }

    private function updateAuditionResult(Score $score): void
    {
        $service = new AuditionResultService($score->version);
        $service->recalculateForCandidate($score->candidate);
        Log::info('Calculating audition results...');
    }
}
