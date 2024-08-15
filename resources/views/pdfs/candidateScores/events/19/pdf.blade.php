<div>

    <div style="text-align: center; font-weight: bold; font-size: 1.15rem; margin-bottom: 1rem;">
        {{ $dto['versionName'] }} Audition Results
    </div>

    <div style="font-size: x-large; text-align: center; margin: 2rem 0;">
        {{ $dto['candidateFullName'] }}
    </div>

    {{-- REPORT --}}
    <div class="mx-2 mb-2 pl-2 shadow-lg text-xs">
        <style>
            table {
                border-collapse: collapse;
                width: 98%;
            }

            td, th {
                border: 1px solid black;
                padding: 0 0.25rem;
                text-align: center;
            }
        </style>

        <table>
            <thead>

            {{-- JUDGES --}}
            <tr>
                <th colspan="4"></th>
                @for($i=0; $i<$dto['judgeCount'];$i++)
                    <th colspan="{{ count($dto['scoringComponentAbbrs']) }}">
                        Judge {{ ($i + 1) }}
                    </th>
                @endfor
            </tr>

            {{-- SCORING COMPONENT ABBRS --}}
            <tr>
                <th colspan="4"></th>
                @for($i=0; $i<$dto['judgeCount'];$i++)
                    @foreach($dto['scoringComponentAbbrs'] AS $abbr)
                        <th>
                            {{ $abbr }}
                        </th>
                    @endforeach
                @endfor
            </tr>


            <tr>
                <th>candidate id</th>
                <th>vp</th>
                <th>total</th>
                <th>result</th>
                @for($i=0; $i<$dto['maxScoringComponentCount']; $i++)
                    <th>
                        {{ ($i + 1) }}
                    </th>
                @endfor
            </tr>

            </thead>
            <tbody>

            <tr>
                <td>
                    {{ $dto['candidateId'] }}
                </td>
                <td class="text-center">
                    {{ strtoupper($dto['voicePartAbbr']) }}
                </td>
                <td class="text-center">
                    {{ $dto['results']['total'] }}
                </td>
                <td class="text-center">
                    {{ $dto['results']['abbr'] }}
                </td>
                @forelse($dto['scores'] AS $score)
                    <td class="text-center">{{ $score }}</td>
                @empty
                    <td colspan="{{ $dto['maxScoringComponentCount'] }}">No scores found.</td>
                @endforelse
            </tr>

            </tbody>

        </table>

    </div>
</div>

