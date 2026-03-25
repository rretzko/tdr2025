<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Events\Versions\DailyRegistrationStat;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigDate;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SnapshotDailyRegistrationStatsCommand extends Command
{
    protected $signature = 'stats:snapshot-daily-registration';

    protected $description = 'Snapshot daily registration stats for active versions within 60 days of postmark deadline';

    public function handle(): void
    {
        $today = Carbon::today();

        $versions = Version::where('status', 'active')->get();

        foreach ($versions as $version) {
            $postmarkDeadline = VersionConfigDate::query()
                ->where('version_id', $version->id)
                ->where('date_type', 'postmark_deadline')
                ->value('version_date');

            if (! $postmarkDeadline) {
                $this->warn("No postmark_deadline found for version {$version->short_name} (id: {$version->id}). Skipping.");
                continue;
            }

            $deadline = Carbon::parse($postmarkDeadline);
            $windowStart = $deadline->copy()->subDays(60);

            if ($today->lt($windowStart) || $today->gt($deadline)) {
                $this->info("Version {$version->short_name} is outside the 60-day window. Skipping.");
                continue;
            }

            $registeredCandidates = Candidate::where('version_id', $version->id)
                ->where('status', 'registered')
                ->count();

            $registeredSchools = Candidate::where('version_id', $version->id)
                ->where('status', 'registered')
                ->distinct('school_id')
                ->count('school_id');

            $voicePartCounts = Candidate::where('version_id', $version->id)
                ->where('status', 'registered')
                ->selectRaw('voice_part_id, COUNT(*) as count')
                ->groupBy('voice_part_id')
                ->pluck('count', 'voice_part_id')
                ->toArray();

            DailyRegistrationStat::updateOrCreate(
                [
                    'version_id' => $version->id,
                    'snapshot_date' => $today->toDateString(),
                ],
                [
                    'registered_candidates' => $registeredCandidates,
                    'registered_schools' => $registeredSchools,
                    'voice_part_counts' => $voicePartCounts,
                ]
            );

            $this->info("Snapshot saved for {$version->short_name}: {$registeredCandidates} candidates, {$registeredSchools} schools.");
        }
    }
}
