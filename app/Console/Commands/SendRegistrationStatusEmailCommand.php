<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Mail\RegistrationStatusMail;
use App\Models\Epayment;
use App\Models\Events\Versions\Participations\Application;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Obligation;
use App\Models\Events\Versions\Participations\Recording;
use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Scoring\Judge;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\Events\Versions\VersionRole;
use App\Models\Events\Versions\VersionScoring;
use App\Models\Students\VoicePart;
use App\Services\RegistrationStatsChartService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendRegistrationStatusEmailCommand extends Command
{
    protected $signature = 'email:registration-status {--test : Send only to MAIL_FROM_ADDRESS}';

    protected $description = 'Send registration status email for all active versions';

    public function handle(): void
    {
        $versions = Version::where('status', 'active')->get();

        foreach ($versions as $version) {
            if ($version->status !== 'active') {
                continue;
            }

            $versionId = $version->id;

            $recipients = VersionRole::where('version_id', $versionId)
                ->whereIn('role', ['event manager', 'registration manager', 'online registration manager'])
                ->with('versionParticipant.user')
                ->get()
                ->filter(fn (VersionRole $vr) => $vr->versionParticipant?->user)
                ->map(fn (VersionRole $vr) => [
                    'name' => $vr->versionParticipant->user->name,
                    'email' => $vr->versionParticipant->user->email,
                    'role' => $vr->role,
                ])
                ->toArray();

/***********************************************************************************************************************
 * FJR 2026-03-18 10:51
 * troubleshooting and adding Founder to mix
 */
$recipients[] = [
  'name' => 'Rick Retzko',
  'email' => config('mail.from.address'),
  'role' => 'founder',
];
Log::info("Sending registration status email for version {$version->short_name} (id: {$versionId}) to " . implode(', ', array_column($recipients, 'email')));
/** end troubleshooting ***********************************************************************************************/

            $stats = [
                'shortName' => $version->short_name,
                'invitedTeachers' => VersionParticipant::where('version_id', $versionId)->count(),
                'obligatedTeachers' => Obligation::where('version_id', $versionId)->count(),
                'downloadedApplications' => Application::where('version_id', $versionId)->count(),
                'candidateStatuses' => Candidate::where('version_id', $versionId)
                    ->selectRaw('status, COUNT(id) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray(),
                'candidatesPaid' => Epayment::where('version_id', $versionId)
                    ->distinct('candidate_id')
                    ->count('candidate_id'),
                'candidatesPaidAmount' => Epayment::where('version_id', $versionId)->sum('amount') / 100,
                'duplicatePayments' => Epayment::where('version_id', $versionId)
                    ->selectRaw('candidate_id')
                    ->groupBy('candidate_id')
                    ->havingRaw('COUNT(*) > 1')
                    ->count(),
                'totalRecordings' => Recording::where('version_id', $versionId)->count(),
                'candidatesWithRecordings' => Recording::where('version_id', $versionId)
                    ->distinct('candidate_id')
                    ->count('candidate_id'),
                'schoolsWithRecordings' => Recording::where('recordings.version_id', $versionId)
                    ->join('candidates', 'candidates.id', '=', 'recordings.candidate_id')
                    ->distinct('candidates.school_id')
                    ->count('candidates.school_id'),
                'schoolsWithRegistrants' => Candidate::where('version_id', $versionId)
                    ->where('status', 'registered')
                    ->distinct('school_id')
                    ->count('school_id'),
                'voiceParts' => $this->getVoiceParts($versionId),
                'schoolCandidates' => $this->getSchoolCandidates($versionId),
                'judgesAssigned' => Judge::where('version_id', $versionId)->count(),
                'judgesEngaged' => Score::where('version_id', $versionId)
                    ->where('score', '>', 0)
                    ->distinct('judge_id')
                    ->count('judge_id'),
                'registrantCount' => Candidate::where('version_id', $versionId)
                    ->where('status', 'registered')
                    ->count(),
                'registrantsScored' => AuditionResult::where('version_id', $versionId)
                    ->where('score_count', '>', 0)
                    ->count(),
                'registrantsUnscored' => $this->getRegistrantsUnscored($versionId),
                'registrantsCompleted' => $this->getRegistrantsCompleted($versionId),
                'recipients' => $recipients,
            ];

            // Refresh the daily snapshot so chart totals match the live candidateStatuses above
            Artisan::call('stats:snapshot-daily-registration');

            // Build chart image URLs for the email, using live totals for chart titles
            $chartService = new RegistrationStatsChartService($versionId);
            $chartService->setLiveTotals(
                $stats['candidateStatuses']['registered'] ?? 0,
                $stats['schoolsWithRegistrants'],
                collect($stats['schoolCandidates'])->sum('registered'),
            );
            $candidatesChartUrl = $chartService->getCandidatesChartUrl();
            $schoolsChartUrl = $chartService->getSchoolsChartUrl();
            $voicePartsChartUrl = $chartService->getVoicePartsChartUrl();

            $stats['chartUrls'] = [
                'candidates' => $candidatesChartUrl,
                'schools' => $schoolsChartUrl,
                'voiceParts' => $voicePartsChartUrl,
            ];

            if ($this->option('test')) {
                $toAddresses = [config('mail.from.address')];
            } else {
                $toAddresses = array_map(fn ($r) => $r['email'], $recipients);

                if (empty($toAddresses)) {
                    $toAddresses = [config('mail.from.address')];
                    Log::warning("No recipients found for version {$version->short_name} (id: {$versionId}). Sending to fallback: " . config('mail.from.address'));
                }
            }

            $version->refresh();
            if ($version->status !== 'active') {
                $this->info("Skipping {$version->short_name} — status changed to '{$version->status}' before send.");
                continue;
            }

            Mail::to($toAddresses)->send(new RegistrationStatusMail($stats));

            $this->info("Status email sent for {$version->short_name} to " . implode(', ', $toAddresses));
        }
    }

    private function getRegistrantsUnscored(int $versionId): array
    {
        $rows = Candidate::where('candidates.version_id', $versionId)
            ->where('candidates.status', 'registered')
            ->leftJoin('audition_results', function ($join) use ($versionId) {
                $join->on('audition_results.candidate_id', '=', 'candidates.id')
                    ->where('audition_results.version_id', '=', $versionId);
            })
            ->where(function ($q) {
                $q->whereNull('audition_results.score_count')
                    ->orWhere('audition_results.score_count', 0);
            })
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->selectRaw('voice_parts.abbr, COUNT(candidates.id) as count')
            ->groupBy('voice_parts.abbr')
            ->orderBy('voice_parts.abbr')
            ->pluck('count', 'abbr')
            ->toArray();

        return [
            'total' => array_sum($rows),
            'byVoicePart' => $rows,
        ];
    }

    private function getRegistrantsCompleted(int $versionId): int
    {
        $criteriaCount = VersionScoring::where('version_id', $versionId)->count();
        $judgePerRoom = (int) VersionConfigAdjudication::where('version_id', $versionId)
            ->value('judge_per_room_count');
        $target = $criteriaCount * $judgePerRoom;

        if ($target === 0) {
            return 0;
        }

        return AuditionResult::where('version_id', $versionId)
            ->where('score_count', $target)
            ->count();
    }

    private function getVoiceParts(int $versionId): array
    {
        $voicePartIds = Candidate::where('version_id', $versionId)
            ->whereIn('status', ['engaged', 'registered'])
            ->distinct()
            ->pluck('voice_part_id');

        return VoicePart::whereIn('id', $voicePartIds)
            ->orderBy('order_by')
            ->pluck('abbr', 'id')
            ->toArray();
    }

    private function getSchoolCandidates(int $versionId): array
    {
        return Candidate::where('version_id', $versionId)
            ->whereIn('status', ['engaged', 'registered'])
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->selectRaw('schools.name as school_name, candidates.status, candidates.voice_part_id, COUNT(candidates.id) as count')
            ->groupBy('schools.name', 'candidates.status', 'candidates.voice_part_id')
            ->orderBy('schools.name')
            ->get()
            ->groupBy('school_name')
            ->map(function ($rows) {
                $engaged = $rows->where('status', 'engaged');
                $registered = $rows->where('status', 'registered');

                $voicePartCounts = [];
                foreach ($registered as $row) {
                    $vpId = $row->voice_part_id;
                    $voicePartCounts[$vpId] = ($voicePartCounts[$vpId] ?? 0) + $row->count;
                }

                return [
                    'engaged' => $engaged->sum('count'),
                    'registered' => $registered->sum('count'),
                    'voiceParts' => $voicePartCounts,
                ];
            })
            ->filter(fn ($counts) => $counts['engaged'] > 0 || $counts['registered'] > 0)
            ->toArray();
    }
}
