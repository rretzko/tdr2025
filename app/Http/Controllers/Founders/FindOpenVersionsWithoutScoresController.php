<?php

namespace App\Http\Controllers\Founders;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FindOpenVersionsWithoutScoresController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $openVersionsWithoutScores = $this->versionsWithoutScores();
        return view('founders.find-open-versions-without-scores', compact('openVersionsWithoutScores'));
    }

    private function versionsWithoutScores(): Collection
    {
        $versions = Version::where('status', 'active')->get();

        foreach ($versions as $version) {
            if ($version->scores()->count() > 0) {
                $versions->forget($version->id);
            }
        }

        return $versions;
    }
}
