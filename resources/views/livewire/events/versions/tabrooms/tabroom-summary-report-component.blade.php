<div class="px-4 space-y-6">

    {{-- HEADER & DOWNLOAD --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                {{ $report['version']['name'] ?? '' }}
            </h2>
            <p class="text-sm text-gray-500">Event Summary Report</p>
        </div>
        <a href="{{ route('pdf.summary.report', $versionId) }}"
           target="_blank"
           class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg shadow">
            ⬇ Download PDF
        </a>
    </div>

    {{-- ANOMALIES BANNER --}}
    @if(!empty($report['anomalies']))
        <div class="space-y-2">
            @foreach($report['anomalies'] as $anomaly)
                @if($anomaly['severity'] === 'warning')
                    <div class="bg-yellow-50 border border-yellow-400 rounded-lg px-4 py-3 text-sm text-yellow-900">
                        <span class="font-semibold">⚠ {{ $anomaly['title'] }}</span><br>
                        {{ $anomaly['detail'] }}
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-300 rounded-lg px-4 py-3 text-sm text-blue-900">
                        <span class="font-semibold">ℹ {{ $anomaly['title'] }}</span><br>
                        {{ $anomaly['detail'] }}
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    {{-- SECTION 1: REGISTRATION OVERVIEW --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 space-y-4">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 border-b pb-1">
            Registration Overview
        </h3>

        {{-- Summary cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @php
                $reg = $report['registration'];
            @endphp
            <x-summary-card label="Total Registered"     value="{{ number_format($reg['total']) }}" />
            <x-summary-card label="Schools Participating" value="{{ number_format($reg['schools_count']) }}" />
            <x-summary-card label="Schools w/ Acceptance" value="{{ number_format($reg['schools_with_acceptance']) }}" />
            <x-summary-card label="Schools w/ No Acceptance" value="{{ number_format($reg['schools_zero_acceptance']) }}" color="yellow" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- By voice part --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Registered by Voice Part</h4>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="pb-1">Voice Part</th>
                            <th class="pb-1 text-right">Count</th>
                            <th class="pb-1 text-right">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reg['by_voice_part'] as $row)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-1">{{ $row['name'] }}</td>
                                <td class="py-1 text-right">{{ $row['count'] }}</td>
                                <td class="py-1 text-right text-gray-500">{{ $row['pct'] }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- By region --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Registered by Region</h4>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="pb-1">Region</th>
                            <th class="pb-1 text-right">Count</th>
                            <th class="pb-1 text-right">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reg['by_region'] as $row)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-1">{{ $row['label'] }}</td>
                                <td class="py-1 text-right">{{ $row['count'] }}</td>
                                <td class="py-1 text-right text-gray-500">{{ $row['pct'] }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SECTION 2: ADJUDICATION RESULTS --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 space-y-4">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 border-b pb-1">
            Adjudication Results
        </h3>

        @php $adj = $report['adjudication']; @endphp

        {{-- Top stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <x-summary-card label="Candidates Auditioned" value="{{ number_format($adj['total']) }}" />
            <x-summary-card label="Accepted"              value="{{ number_format($adj['accepted']) }}" color="green" />
            <x-summary-card label="Not Accepted"          value="{{ number_format($adj['not_accepted']) }}" color="red" />
            <x-summary-card label="Acceptance Rate"       value="{{ $adj['rate'] }}%" color="blue" />
        </div>

        {{-- Ensemble breakdown --}}
        @if(!empty($adj['ensembles']))
            <div class="flex gap-4">
                @foreach($adj['ensembles'] as $ens)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg px-4 py-2 text-sm text-center">
                        <div class="font-semibold text-gray-700 dark:text-gray-200">{{ $ens['label'] }}</div>
                        <div class="text-2xl font-bold text-blue-600">{{ $ens['count'] }}</div>
                        <div class="text-xs text-gray-500">accepted</div>
                    </div>
                @endforeach
            </div>
        @endif

        <p class="text-xs text-gray-400 italic">
            Scoring scale: {{ $adj['score_min'] }} (best possible) to {{ $adj['score_max'] }} (worst possible).
            Lower total score = stronger audition.
        </p>

        {{-- By voice part --}}
        <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300">Results by Voice Part</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-1 pr-3">Voice Part</th>
                        <th class="pb-1 text-right pr-3">Auditioned</th>
                        <th class="pb-1 text-right pr-3">Accepted</th>
                        <th class="pb-1 text-right pr-3">Rate</th>
                        <th class="pb-1 text-right pr-3">Avg (Acc)</th>
                        <th class="pb-1 text-right pr-3">Avg (Rej)</th>
                        <th class="pb-1 text-right pr-3">Last In</th>
                        <th class="pb-1 text-right">First Out</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($adj['by_voice_part'] as $row)
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-1 pr-3 font-medium">{{ $row['name'] }}</td>
                            <td class="py-1 text-right pr-3">{{ $row['auditioned'] }}</td>
                            <td class="py-1 text-right pr-3 text-green-700 font-semibold">{{ $row['accepted'] }}</td>
                            <td class="py-1 text-right pr-3">{{ $row['rate'] }}%</td>
                            <td class="py-1 text-right pr-3 text-green-600">{{ $row['avg_accepted'] ?? '—' }}</td>
                            <td class="py-1 text-right pr-3 text-red-500">{{ $row['avg_rejected'] ?? '—' }}</td>
                            <td class="py-1 text-right pr-3">{{ $row['cutoff_last_in'] ?? '—' }}</td>
                            <td class="py-1 text-right {{ ($row['cutoff_gap'] ?? 99) <= 2 ? 'text-gray-400' : 'text-orange-500 font-semibold' }}">
                                {{ $row['cutoff_first_out'] ?? '—' }}
                                @if($row['unsigned'] > 0)
                                    <span class="text-xs text-blue-400 ml-1">*</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if(collect($adj['by_voice_part'])->sum('unsigned') > 0)
                <p class="text-xs text-blue-500 mt-1">* Excludes administratively removed candidates from cutoff calculation — see Anomalies above.</p>
            @endif
        </div>

        {{-- By region --}}
        <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mt-4">Results by Region</h4>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="pb-1">Region</th>
                    <th class="pb-1 text-right pr-3">Auditioned</th>
                    <th class="pb-1 text-right pr-3">Accepted</th>
                    <th class="pb-1 text-right">Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($adj['by_region'] as $row)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-1">{{ $row['label'] }}</td>
                        <td class="py-1 text-right pr-3">{{ $row['auditioned'] }}</td>
                        <td class="py-1 text-right pr-3 text-green-700 font-semibold">{{ $row['accepted'] }}</td>
                        <td class="py-1 text-right">{{ $row['rate'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- SECTION 3: SCORING PROFILE --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 space-y-3">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 border-b pb-1">
            Scoring Profile
        </h3>

        @php $scoring = $report['scoring']; @endphp
        <p class="text-sm text-gray-500">
            {{ $scoring['total_factors'] }} scoring factors evaluated per candidate
            ({{ collect($scoring['categories'])->pluck('name')->implode(', ') }}).
            Scores range 1 (best) to 9 (worst) per factor.
        </p>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="pb-1">Category</th>
                    <th class="pb-1 text-right pr-3">Factors</th>
                    <th class="pb-1 text-right pr-3">Avg Score</th>
                    <th class="pb-1 text-right">Tolerance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scoring['categories'] as $cat)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-1 font-medium">{{ $cat['name'] }}</td>
                        <td class="py-1 text-right pr-3">{{ $cat['factor_count'] }}</td>
                        <td class="py-1 text-right pr-3">{{ $cat['avg_score'] }}</td>
                        <td class="py-1 text-right">±{{ $cat['tolerance'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- SECTION 4: SCORING FAIRNESS --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 space-y-4">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 border-b pb-1">
            Scoring Fairness
        </h3>

        @php $fairness = $report['fairness']; @endphp

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <x-summary-card label="Total Evaluations" value="{{ number_format($fairness['total_evaluations']) }}" />
            <x-summary-card
                label="Tolerance Violations"
                value="{{ $fairness['violations'] }}"
                color="{{ $fairness['violations'] === 0 ? 'green' : 'red' }}" />
            <x-summary-card label="Active Judges"   value="{{ $fairness['judge_count'] }}" />
            <x-summary-card label="Overall Avg Score" value="{{ $fairness['overall_avg'] }}" />
        </div>

        @if($fairness['violations'] === 0)
            <div class="bg-green-50 border border-green-300 rounded-lg px-4 py-2 text-sm text-green-800">
                ✓ All {{ number_format($fairness['total_evaluations']) }} factor evaluations fell within
                tolerance. Inter-judge agreement was excellent across all rooms.
            </div>
        @endif

        {{-- Category scoring pattern --}}
        <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300">Average Score by Category</h4>
        <p class="text-xs text-gray-400 italic">
            Higher average = harder scoring. Differences reflect the relative difficulty of each audition segment.
        </p>
        <div class="flex gap-4">
            @foreach($fairness['category_avgs'] as $cat)
                @php
                    $color = $cat['diff'] > 0.4 ? 'text-orange-600' : ($cat['diff'] < -0.4 ? 'text-blue-600' : 'text-gray-700 dark:text-gray-200');
                @endphp
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg px-4 py-2 text-center text-sm">
                    <div class="font-semibold {{ $color }}">{{ $cat['name'] }}</div>
                    <div class="text-2xl font-bold {{ $color }}">{{ $cat['avg'] }}</div>
                    <div class="text-xs text-gray-500">
                        {{ $cat['diff'] >= 0 ? '+' : '' }}{{ $cat['diff'] }} vs overall
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Flagged judges --}}
        @if(!empty($fairness['flagged_judges']))
            <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                Judges with Notable Scoring Patterns
                <span class="font-normal text-gray-400">(avg deviates &gt; 0.5 from overall)</span>
            </h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="pb-1 pr-3">Judge</th>
                            <th class="pb-1 pr-3">Room</th>
                            <th class="pb-1 pr-3">Role</th>
                            <th class="pb-1 text-right pr-3">Avg</th>
                            <th class="pb-1 text-right pr-3">vs. Overall</th>
                            <th class="pb-1 text-right">Std Dev</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fairness['flagged_judges'] as $j)
                            @php
                                $diffColor = $j['diff'] > 0
                                    ? 'text-orange-600' // harsher scorer
                                    : 'text-blue-600';  // more lenient
                            @endphp
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-1 pr-3">{{ $j['name'] }}</td>
                                <td class="py-1 pr-3">{{ $j['room'] }}</td>
                                <td class="py-1 pr-3 text-gray-500">{{ $j['role'] }}</td>
                                <td class="py-1 text-right pr-3">{{ $j['avg'] }}</td>
                                <td class="py-1 text-right pr-3 font-semibold {{ $diffColor }}">
                                    {{ $j['diff'] >= 0 ? '+' : '' }}{{ $j['diff'] }}
                                </td>
                                <td class="py-1 text-right">{{ $j['std_dev'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="text-xs text-gray-400 mt-1 italic">
                    Positive diff = scored higher (harsher). Negative diff = scored lower (more lenient).
                    Cross-room differences often reflect structural differences between audition segments rather than individual judge bias.
                </p>
            </div>
        @endif

        {{-- Within-room outliers --}}
        @if(!empty($fairness['within_room_flags']))
            <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                Within-Room Judge Outliers
                <span class="font-normal text-gray-400">(avg deviates &gt; 0.3 from room average)</span>
            </h4>
            @foreach($fairness['within_room_flags'] as $flag)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-300 rounded-lg px-3 py-2 text-sm">
                    <span class="font-semibold">{{ $flag['room'] }}</span>
                    (room avg: {{ $flag['room_avg'] }})
                    @foreach($flag['outliers'] as $o)
                        — {{ $o['name'] }}: {{ $o['avg'] }}
                        ({{ $o['room_diff'] >= 0 ? '+' : '' }}{{ number_format($o['room_diff'], 3) }} from room avg)
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>

</div>
