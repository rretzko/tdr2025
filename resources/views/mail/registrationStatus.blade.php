<div>
    <div>Following are your registration statistics for the {{ $stats['shortName'] }}:</div>
    <br>
    <div>Invited teachers: {{ $stats['invitedTeachers'] }}</div>
    <div>Obligated teachers: {{ $stats['obligatedTeachers'] }}</div>
    <div>Downloaded Applications: {{ $stats['downloadedApplications'] }}</div>
    <div>Candidate Status:
        @foreach($stats['candidateStatuses'] as $status => $count)
            {{ $status }}={{ $count }}{{ $loop->last ? '' : ', ' }}
        @endforeach
    </div>
    <div>Candidates paid: {{ $stats['candidatesPaid'] }} (${{ number_format($stats['candidatesPaidAmount'], 2) }}) with {{ $stats['duplicatePayments'] }} duplicate payment{{ $stats['duplicatePayments'] !== 1 ? 's' : '' }}</div>
    <div>Total Recordings: {{ $stats['totalRecordings'] }}</div>
    <div>Candidates with recordings: {{ $stats['candidatesWithRecordings'] }}</div>
    <div>Schools with recordings: {{ $stats['schoolsWithRecordings'] }}</div>
    <div>Schools with registrants: {{ $stats['schoolsWithRegistrants'] }}</div>

    @php
        $judgesPending = $stats['judgesAssigned'] - $stats['judgesEngaged'];
        $judgesPendingPct = $stats['judgesAssigned'] > 0
            ? (int) round(($judgesPending / $stats['judgesAssigned']) * 100)
            : 0;
    @endphp
    <div id="judging-stats" style="margin-top: 1rem; border-top: 1px solid #ccc; padding-top: 1rem;border-bottom: 1px solid #ccc; padding-bottom: 1rem;">
        <div>Judges assigned: {{ $stats['judgesAssigned'] }}</div>
        <div>Judges engaged: {{ $stats['judgesEngaged'] }}</div>
        <div>Judges pending: {{ $judgesPending }} ({{ $judgesPendingPct }}%)</div>
        <div style="margin-top: 0.5rem;">Registrant count: {{ $stats['registrantCount'] }}</div>
        <div>Registrants scored: {{ $stats['registrantsScored'] }}</div>
        @php
            $unscored = $stats['registrantsUnscored'];
            $unscoredBreakdown = collect($unscored['byVoicePart'])->map(fn($count, $abbr) => "{$abbr}: {$count}")->implode(', ');
        @endphp
        <div>Registrants unscored: {{ $unscored['total'] }}{{ $unscoredBreakdown ? ' — ' . $unscoredBreakdown : '' }}</div>
        <div>Registrants completed: {{ $stats['registrantsCompleted'] }}</div>
    </div>

    <br>
    <table border="1" cellpadding="4" cellspacing="0">
        <thead>
            <tr>
                <th style="text-align: left;">School</th>
                <th style="text-align: right;">Eng'd</th>
                <th style="text-align: right;">Reg'd</th>
                @foreach($stats['voiceParts'] as $vpId => $abbr)
                    <th style="text-align: right;">{{ $abbr }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr style="font-weight: bold;">
                <td>Totals</td>
                <td style="text-align: right;">{{ collect($stats['schoolCandidates'])->sum('engaged') }}</td>
                <td style="text-align: right;">{{ collect($stats['schoolCandidates'])->sum('registered') }}</td>
                @foreach($stats['voiceParts'] as $vpId => $abbr)
                    @php $vpTotal = collect($stats['schoolCandidates'])->sum(fn($c) => $c['voiceParts'][$vpId] ?? 0); @endphp
                    <td style="text-align: right;">{{ $vpTotal ?: '-' }}</td>
                @endforeach
            </tr>
            @foreach($stats['schoolCandidates'] as $school => $counts)
                <tr>
                    <td>{{ $school }}</td>
                    <td style="text-align: right;">{{ $counts['engaged'] }}</td>
                    <td style="text-align: right;">{{ $counts['registered'] }}</td>
                    @foreach($stats['voiceParts'] as $vpId => $abbr)
                        <td style="text-align: right;">{{ ($counts['voiceParts'][$vpId] ?? 0) ?: '-' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>

    @if(! empty($stats['chartUrls']['candidates']) || ! empty($stats['chartUrls']['schools']) || ! empty($stats['chartUrls']['voiceParts']))
        <div style="font-weight: bold; margin-bottom: 8px;">Registration Trends:</div>

        @if(! empty($stats['chartUrls']['candidates']))
            <div style="margin-bottom: 12px;">
                <img src="{{ $stats['chartUrls']['candidates'] }}" alt="Registered Candidates Chart" style="max-width: 600px; width: 100%;" />
            </div>
        @endif

        @if(! empty($stats['chartUrls']['schools']))
            <div style="margin-bottom: 12px;">
                <img src="{{ $stats['chartUrls']['schools'] }}" alt="Schools with Registered Candidates Chart" style="max-width: 600px; width: 100%;" />
            </div>
        @endif

        @if(! empty($stats['chartUrls']['voiceParts']))
            <div style="margin-bottom: 12px;">
                <img src="{{ $stats['chartUrls']['voiceParts'] }}" alt="Registrations by Voice Part Chart" style="max-width: 600px; width: 100%;" />
            </div>
        @endif
        <br>
    @endif

    <div>If there are other statistics you would like to see added to this email, please let Rick know (rick@mfrholdings.com)!</div>
    <br>
    <div>Status emails are sent daily at 11:00 am to:</div>
    @foreach($stats['recipients'] as $recipient)
        <div style="margin-left: 1rem;">{{ $recipient['name'] }} ({{ $recipient['email'] }}), {{ $recipient['role'] }}</div>
    @endforeach
    <br>
    If others should be included in this mailing, please update their roles to "Event Manager", "Registration Manager" or "Online Registration Manager".
</div>
