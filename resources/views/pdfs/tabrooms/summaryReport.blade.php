<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9pt; color: #1a1a1a; }

        h1  { font-size: 13pt; font-weight: bold; text-align: center; margin-bottom: 2pt; }
        h2  { font-size: 11pt; font-weight: bold; margin: 10pt 0 4pt; border-bottom: 1pt solid #ccc; padding-bottom: 2pt; }
        h3  { font-size: 9pt; font-weight: bold; margin: 6pt 0 3pt; color: #444; }
        p   { margin-bottom: 4pt; }

        .subtitle  { text-align: center; font-size: 8pt; color: #555; margin-bottom: 8pt; }
        .page-break { page-break-after: always; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 6pt; }
        th    { font-weight: bold; font-size: 8pt; text-align: left; padding: 3pt 5pt; background: #f0f0f0; border-bottom: 1pt solid #ccc; }
        td    { padding: 2pt 5pt; border-bottom: 1pt solid #eee; font-size: 8.5pt; }
        .right { text-align: right; }
        .center { text-align: center; }

        .card-row { width: 100%; margin-bottom: 8pt; }
        .card-row td { border: none; padding: 0 4pt 0 0; vertical-align: top; }
        .stat-box { border: 1pt solid #ccc; border-radius: 3pt; padding: 5pt 8pt; text-align: center; background: #fafafa; }
        .stat-val  { font-size: 16pt; font-weight: bold; }
        .stat-lbl  { font-size: 7pt; color: #555; margin-top: 2pt; }

        .alert-note    { background: #eff6ff; border: 1pt solid #93c5fd; padding: 5pt 8pt; border-radius: 3pt; margin-bottom: 5pt; }
        .alert-warning { background: #fffbeb; border: 1pt solid #fcd34d; padding: 5pt 8pt; border-radius: 3pt; margin-bottom: 5pt; }
        .alert-success { background: #f0fdf4; border: 1pt solid #86efac; padding: 5pt 8pt; border-radius: 3pt; margin-bottom: 5pt; }
        .alert-title   { font-weight: bold; font-size: 8.5pt; margin-bottom: 2pt; }
        .italic        { font-style: italic; color: #666; font-size: 7.5pt; }

        .green  { color: #166534; }
        .red    { color: #991b1b; }
        .orange { color: #9a3412; }
        .blue   { color: #1e40af; }
    </style>
</head>
<body>

    {{-- TITLE --}}
    <h1>{{ $report['version']['event_name'] ?? '' }} — Event Summary Report</h1>
    <p class="subtitle">{{ $report['version']['name'] ?? '' }} &nbsp;|&nbsp; Generated {{ now()->format('F j, Y') }}</p>

    {{-- ANOMALIES --}}
    @if(!empty($report['anomalies']))
        <h2>Anomalies &amp; Notes</h2>
        @foreach($report['anomalies'] as $anomaly)
            <div class="{{ $anomaly['severity'] === 'warning' ? 'alert-warning' : 'alert-note' }}">
                <div class="alert-title">
                    {{ $anomaly['severity'] === 'warning' ? '⚠ ' : 'ℹ ' }}{{ $anomaly['title'] }}
                </div>
                {{ $anomaly['detail'] }}
            </div>
        @endforeach
    @endif

    {{-- REGISTRATION --}}
    <h2>Registration Overview</h2>
    @php $reg = $report['registration']; @endphp

    <table class="card-row">
        <tr>
            <td width="25%">
                <div class="stat-box">
                    <div class="stat-val">{{ number_format($reg['total']) }}</div>
                    <div class="stat-lbl">Total Registered</div>
                </div>
            </td>
            <td width="25%">
                <div class="stat-box">
                    <div class="stat-val">{{ number_format($reg['schools_count']) }}</div>
                    <div class="stat-lbl">Schools Participating</div>
                </div>
            </td>
            <td width="25%">
                <div class="stat-box">
                    <div class="stat-val green">{{ number_format($reg['schools_with_acceptance']) }}</div>
                    <div class="stat-lbl">Schools w/ Acceptance</div>
                </div>
            </td>
            <td width="25%">
                <div class="stat-box">
                    <div class="stat-val orange">{{ number_format($reg['schools_zero_acceptance']) }}</div>
                    <div class="stat-lbl">Schools w/ No Acceptance</div>
                </div>
            </td>
        </tr>
    </table>

    <table style="width:48%; float:left; margin-right:4%;">
        <thead>
            <tr><th>Voice Part</th><th class="right">Count</th><th class="right">%</th></tr>
        </thead>
        <tbody>
            @foreach($reg['by_voice_part'] as $row)
                <tr><td>{{ $row['name'] }}</td><td class="right">{{ $row['count'] }}</td><td class="right">{{ $row['pct'] }}%</td></tr>
            @endforeach
        </tbody>
    </table>

    <table style="width:48%; float:left;">
        <thead>
            <tr><th>Region</th><th class="right">Count</th><th class="right">%</th></tr>
        </thead>
        <tbody>
            @foreach($reg['by_region'] as $row)
                <tr><td>{{ $row['label'] }}</td><td class="right">{{ $row['count'] }}</td><td class="right">{{ $row['pct'] }}%</td></tr>
            @endforeach
        </tbody>
    </table>
    <div style="clear:both; margin-bottom:8pt;"></div>

    {{-- ADJUDICATION RESULTS --}}
    <div class="page-break"></div>
    <h1>{{ $report['version']['event_name'] ?? '' }} — Event Summary Report (cont.)</h1>
    <p class="subtitle">{{ $report['version']['name'] ?? '' }}</p>

    <h2>Adjudication Results</h2>
    @php $adj = $report['adjudication']; @endphp

    <table class="card-row">
        <tr>
            <td width="25%">
                <div class="stat-box">
                    <div class="stat-val">{{ number_format($adj['total']) }}</div>
                    <div class="stat-lbl">Candidates Auditioned</div>
                </div>
            </td>
            <td width="25%">
                <div class="stat-box">
                    <div class="stat-val green">{{ number_format($adj['accepted']) }}</div>
                    <div class="stat-lbl">Accepted</div>
                </div>
            </td>
            <td width="25%">
                <div class="stat-box">
                    <div class="stat-val red">{{ number_format($adj['not_accepted']) }}</div>
                    <div class="stat-lbl">Not Accepted</div>
                </div>
            </td>
            <td width="25%">
                <div class="stat-box">
                    <div class="stat-val blue">{{ $adj['rate'] }}%</div>
                    <div class="stat-lbl">Acceptance Rate</div>
                </div>
            </td>
        </tr>
    </table>

    @if(!empty($adj['ensembles']))
        <h3>Placement by Ensemble</h3>
        <table>
            <thead><tr><th>Ensemble</th><th class="right">Accepted</th></tr></thead>
            <tbody>
                @foreach($adj['ensembles'] as $ens)
                    <tr><td>{{ $ens['label'] }}</td><td class="right">{{ $ens['count'] }}</td></tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <p class="italic">Scoring scale: {{ $adj['score_min'] }} (best) to {{ $adj['score_max'] }} (worst). Lower total score = stronger audition.</p>

    <h3>Results by Voice Part</h3>
    <table>
        <thead>
            <tr>
                <th>Voice Part</th>
                <th class="right">Auditioned</th>
                <th class="right">Accepted</th>
                <th class="right">Rate</th>
                <th class="right">Avg (Acc)</th>
                <th class="right">Avg (Rej)</th>
                <th class="right">Last In</th>
                <th class="right">First Out</th>
            </tr>
        </thead>
        <tbody>
            @foreach($adj['by_voice_part'] as $row)
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td class="right">{{ $row['auditioned'] }}</td>
                    <td class="right green">{{ $row['accepted'] }}</td>
                    <td class="right">{{ $row['rate'] }}%</td>
                    <td class="right">{{ $row['avg_accepted'] ?? '—' }}</td>
                    <td class="right">{{ $row['avg_rejected'] ?? '—' }}</td>
                    <td class="right">{{ $row['cutoff_last_in'] ?? '—' }}</td>
                    <td class="right">
                        {{ $row['cutoff_first_out'] ?? '—' }}
                        @if($row['unsigned'] > 0)<span class="blue"> *</span>@endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if(collect($adj['by_voice_part'])->sum('unsigned') > 0)
        <p class="italic">* Cutoff excludes administratively removed candidates — see Anomalies section.</p>
    @endif

    <h3>Results by Region</h3>
    <table>
        <thead>
            <tr><th>Region</th><th class="right">Auditioned</th><th class="right">Accepted</th><th class="right">Rate</th></tr>
        </thead>
        <tbody>
            @foreach($adj['by_region'] as $row)
                <tr>
                    <td>{{ $row['label'] }}</td>
                    <td class="right">{{ $row['auditioned'] }}</td>
                    <td class="right green">{{ $row['accepted'] }}</td>
                    <td class="right">{{ $row['rate'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- SCORING FAIRNESS --}}
    <div class="page-break"></div>
    <h1>{{ $report['version']['event_name'] ?? '' }} — Event Summary Report (cont.)</h1>
    <p class="subtitle">{{ $report['version']['name'] ?? '' }}</p>

    <h2>Scoring Profile &amp; Fairness</h2>
    @php
        $scoring  = $report['scoring'];
        $fairness = $report['fairness'];
    @endphp

    <table style="width:48%; float:left; margin-right:4%;">
        <thead><tr><th>Category</th><th class="right">Factors</th><th class="right">Avg Score</th><th class="right">Tolerance</th></tr></thead>
        <tbody>
            @foreach($scoring['categories'] as $cat)
                <tr>
                    <td>{{ $cat['name'] }}</td>
                    <td class="right">{{ $cat['factor_count'] }}</td>
                    <td class="right">{{ $cat['avg_score'] }}</td>
                    <td class="right">±{{ $cat['tolerance'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width:48%; float:left;">
        <thead><tr><th>Category Avg vs. Overall</th><th class="right">Avg</th><th class="right">Diff</th></tr></thead>
        <tbody>
            @foreach($fairness['category_avgs'] as $cat)
                <tr>
                    <td>{{ $cat['name'] }}</td>
                    <td class="right">{{ $cat['avg'] }}</td>
                    <td class="right {{ $cat['diff'] > 0.4 ? 'orange' : ($cat['diff'] < -0.4 ? 'blue' : '') }}">
                        {{ $cat['diff'] >= 0 ? '+' : '' }}{{ $cat['diff'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div style="clear:both; margin-bottom:6pt;"></div>

    <table class="card-row">
        <tr>
            <td width="33%">
                <div class="stat-box">
                    <div class="stat-val">{{ number_format($fairness['total_evaluations']) }}</div>
                    <div class="stat-lbl">Total Evaluations</div>
                </div>
            </td>
            <td width="33%">
                <div class="stat-box">
                    <div class="stat-val {{ $fairness['violations'] === 0 ? 'green' : 'red' }}">
                        {{ $fairness['violations'] }}
                    </div>
                    <div class="stat-lbl">Tolerance Violations</div>
                </div>
            </td>
            <td width="33%">
                <div class="stat-box">
                    <div class="stat-val">{{ $fairness['judge_count'] }}</div>
                    <div class="stat-lbl">Active Judges</div>
                </div>
            </td>
        </tr>
    </table>

    @if($fairness['violations'] === 0)
        <div class="alert-success">
            <div class="alert-title">✓ Perfect Inter-Judge Consistency</div>
            All {{ number_format($fairness['total_evaluations']) }} factor evaluations fell within
            tolerance thresholds. No tolerance violations were recorded across all {{ $fairness['judge_count'] }} judges.
        </div>
    @endif

    @if(!empty($fairness['flagged_judges']))
        <h3>Judges with Notable Scoring Patterns (deviation &gt; 0.5 from overall avg {{ $fairness['overall_avg'] }})</h3>
        <table>
            <thead>
                <tr>
                    <th>Judge</th><th>Room</th><th>Role</th>
                    <th class="right">Avg</th><th class="right">vs. Overall</th><th class="right">Std Dev</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fairness['flagged_judges'] as $j)
                    <tr>
                        <td>{{ $j['name'] }}</td>
                        <td>{{ $j['room'] }}</td>
                        <td>{{ $j['role'] }}</td>
                        <td class="right">{{ $j['avg'] }}</td>
                        <td class="right {{ $j['diff'] > 0 ? 'orange' : 'blue' }}">{{ $j['diff'] >= 0 ? '+' : '' }}{{ $j['diff'] }}</td>
                        <td class="right">{{ $j['std_dev'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="italic">Positive = harsher scoring. Negative = more lenient. Differences across room types often reflect audition segment difficulty rather than individual judge bias.</p>
    @endif

    @if(!empty($fairness['within_room_flags']))
        <h3>Within-Room Outliers (deviation &gt; 0.3 from room average)</h3>
        @foreach($fairness['within_room_flags'] as $flag)
            <div class="alert-warning">
                <div class="alert-title">{{ $flag['room'] }} (room avg: {{ $flag['room_avg'] }})</div>
                @foreach($flag['outliers'] as $o)
                    {{ $o['name'] }}: avg {{ $o['avg'] }} ({{ $o['room_diff'] >= 0 ? '+' : '' }}{{ number_format($o['room_diff'], 3) }} from room avg)<br>
                @endforeach
            </div>
        @endforeach
    @endif

</body>
</html>
