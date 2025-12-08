<?php

namespace App\Http\Controllers\Founders;

use App\Http\Controllers\Controller;
use App\Jobs\SeedScoresJob;
use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Participations\Registrant;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Services\ScoreSeederService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CreateScoresController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Version $version)
    {
        //delete all scores and results for this version
        Score::where('version_id', $version->id)->delete();
        AuditionResult::where('version_id', $version->id)->delete();

        //seed scores
        SeedScoresJob::dispatch($version);

    }
}
