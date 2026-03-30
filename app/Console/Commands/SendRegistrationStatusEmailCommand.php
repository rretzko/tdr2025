<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Mail\RegistrationStatusMail;
use App\Models\Epayment;
use App\Models\Events\Versions\Participations\Application;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Obligation;
use App\Models\Events\Versions\Participations\Recording;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\Events\Versions\VersionRole;
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
                'recipients' => $recipients,
            ];

            // Refresh the daily snapshot so chart totals match the live candidateStatuses above
            Artisan::call('stats:snapshot-daily-registration');

            // Build chart image URLs for the email
            $chartService = new RegistrationStatsChartService($versionId);
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

            Mail::to($toAddresses)->send(new RegistrationStatusMail($stats));

            $this->info("Status email sent for {$version->short_name} to " . implode(', ', $toAddresses));
        }
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
