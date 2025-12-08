<?php

namespace App\Jobs;

use App\Models\Events\Versions\Version;
use App\Services\ScoreSeederService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SeedScoresJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hour

    public function __construct(
        public Version $version
    ) {}

    public function handle(): void
    {
        $service = new ScoreSeederService($this->version);
        $service->seedScores();
    }
}
