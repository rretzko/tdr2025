<div>

    <style>
        .pageBreak {
            page-break-after: always;
        }
    </style>

    @foreach($dto['candidates'] AS $candidate)

        <div style="text-align: center; font-weight: bold; font-size: 1.15rem; margin-bottom: 1rem;">
            {{ $dto['versionName'] }} Audition Results
        </div>

        <div style="font-size: x-large; text-align: center; margin: 2rem 0;">
            {{ $candidate['candidateFullName'] }}
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
                    <th colspan="4" style="border-color: transparent; border-right-color: black;"></th>
                    @for($i=0; $i<$dto['judgeCount'];$i++)
                        <th colspan="{{ count($dto['scoreFactorAbbrs']) }}">
                            Judge {{ ($i + 1) }}
                        </th>
                    @endfor
                </tr>

                {{-- SCORING COMPONENT ABBRS --}}
                <tr>
                    <th colspan="4" style="border-color: transparent; border-right-color: black;"></th>
                    @for($i=0; $i<$dto['judgeCount'];$i++)
                        @foreach($dto['scoreCategories'] AS $category)
                            <th colspan="{{ $dto['scoreCategoryFactorCount'][$category] }}">
                                {{ \Illuminate\Support\Str::substr($category, 0, 5) }}
                            </th>
                        @endforeach
                    @endfor
                </tr>

                {{-- SCORE INDEX NUMBERS --}}
                <tr style="font-size: 0.5rem;">
                    <th colspan="4" style="border-left-color: transparent;"></th>
                    @for($i=0; $i<(count($dto['scoreFactorAbbrs']) * $dto['judgeCount']); $i++)
                        <td>
                            {{ ($i + 1) }}
                        </td>
                    @endfor
                </tr>

                <tr>
                    <th>cand #</th>
                    <th>vp</th>
                    <th>total</th>
                    <th>result</th>
                    @for($i=0; $i<$dto['judgeCount']; $i++)
                        @foreach($dto['scoreFactorAbbrs'] AS $scoreFactorAbbr)
                            <th>
                                {{ \Illuminate\Support\Str::substr($scoreFactorAbbr['abbr'],0, 5) }}
                            </th>
                        @endforeach
                    @endfor
                </tr>

                </thead>
                <tbody>

                <tr>
                    <td>
                        {{ $candidate['candidateId'] }}
                    </td>
                    <td class="text-center">
                        {{ strtoupper($candidate['candidateVoicePartAbbr']) }}
                    </td>
                    <td class="text-center">
                        {{ $candidate['auditionResult']['total'] }}
                    </td>
                    <td class="text-center">
                        {{ $candidate['auditionResult']['acceptance_abbr'] }}
                    </td>
                    @if(count($candidate['scores']))
                        @for($i=0; $i<($dto['maxScoreFactorCount'] * $dto['judgeCount']); $i++)
                            <td class="text-center">
                                @if(array_key_exists($i, $candidate['scores']))
                                    {{ $candidate['scores'][$i] }}
                                @else
                                    -
                                @endif
                            </td>
                        @endfor
                    @else
                        <td colspan="{{ ($dto['maxScoreFactorCount'] * $dto['judgeCount']) }}">No scores found.</td>
                    @endif
                </tr>

                </tbody>

            </table>

        </div>

        <div class="pageBreak"></div>
    @endforeach

</div>

