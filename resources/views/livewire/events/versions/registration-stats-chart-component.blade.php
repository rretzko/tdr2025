<div>
    @if($chartData['hasData'])
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Registration Statistics</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Candidates Chart: vertical bars (daily) + line (cumulative), dual Y-axis --}}
                <div>
                    <canvas id="candidatesChart" height="300"></canvas>
                </div>

                {{-- Schools Chart: vertical bars (daily) + line (cumulative), dual Y-axis --}}
                <div>
                    <canvas id="schoolsChart" height="300"></canvas>
                </div>
            </div>

            {{-- Voice Parts Chart: horizontal bar --}}
            <div class="mt-6 max-w-xl mx-auto">
                <canvas id="voicePartsChart" height="250"></canvas>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const chartData = @json($chartData);

                // Candidates chart
                new Chart(document.getElementById('candidatesChart'), {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                type: 'bar',
                                label: 'Daily Registrations',
                                data: chartData.candidates.daily,
                                backgroundColor: 'rgba(59,130,246,0.6)',
                                borderColor: 'rgba(59,130,246,1)',
                                borderWidth: 1,
                                yAxisID: 'y',
                            },
                            {
                                type: 'line',
                                label: 'Cumulative',
                                data: chartData.candidates.cumulative,
                                borderColor: 'rgba(220,38,38,1)',
                                backgroundColor: 'rgba(220,38,38,0.1)',
                                fill: false,
                                tension: 0.3,
                                pointRadius: 2,
                                yAxisID: 'y1',
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: { display: true, text: 'Registered Candidates' },
                        },
                        scales: {
                            y: {
                                position: 'left',
                                title: { display: true, text: 'Daily' },
                                beginAtZero: true,
                            },
                            y1: {
                                position: 'right',
                                title: { display: true, text: 'Cumulative' },
                                beginAtZero: true,
                                grid: { drawOnChartArea: false },
                            },
                        },
                    },
                });

                // Schools chart
                new Chart(document.getElementById('schoolsChart'), {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                type: 'bar',
                                label: 'Daily Schools',
                                data: chartData.schools.daily,
                                backgroundColor: 'rgba(16,185,129,0.6)',
                                borderColor: 'rgba(16,185,129,1)',
                                borderWidth: 1,
                                yAxisID: 'y',
                            },
                            {
                                type: 'line',
                                label: 'Cumulative',
                                data: chartData.schools.cumulative,
                                borderColor: 'rgba(220,38,38,1)',
                                backgroundColor: 'rgba(220,38,38,0.1)',
                                fill: false,
                                tension: 0.3,
                                pointRadius: 2,
                                yAxisID: 'y1',
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: { display: true, text: 'Schools with Registered Candidates' },
                        },
                        scales: {
                            y: {
                                position: 'left',
                                title: { display: true, text: 'Daily' },
                                beginAtZero: true,
                            },
                            y1: {
                                position: 'right',
                                title: { display: true, text: 'Cumulative' },
                                beginAtZero: true,
                                grid: { drawOnChartArea: false },
                            },
                        },
                    },
                });

                // Voice Parts horizontal bar chart
                if (chartData.voiceParts.length > 0) {
                    const vpLabels = chartData.voiceParts.map(vp => vp.label);
                    const vpCounts = chartData.voiceParts.map(vp => vp.count);
                    const vpColors = [
                        'rgba(59,130,246,0.6)',
                        'rgba(16,185,129,0.6)',
                        'rgba(245,158,11,0.6)',
                        'rgba(220,38,38,0.6)',
                        'rgba(139,92,246,0.6)',
                        'rgba(236,72,153,0.6)',
                        'rgba(20,184,166,0.6)',
                        'rgba(249,115,22,0.6)',
                    ];

                    new Chart(document.getElementById('voicePartsChart'), {
                        type: 'bar',
                        data: {
                            labels: vpLabels,
                            datasets: [{
                                label: 'Registered Candidates',
                                data: vpCounts,
                                backgroundColor: vpColors.slice(0, vpLabels.length),
                                borderWidth: 1,
                            }],
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            plugins: {
                                title: { display: true, text: 'Registrations by Voice Part' },
                                legend: { display: false },
                            },
                            scales: {
                                x: {
                                    title: { display: true, text: 'Count' },
                                    beginAtZero: true,
                                },
                            },
                        },
                    });
                }
            });
        </script>
    @else
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
            <p class="text-gray-500 dark:text-gray-400 text-center">
                Registration statistics charts will appear here once daily snapshots begin accumulating.
            </p>
        </div>
    @endif
</div>
