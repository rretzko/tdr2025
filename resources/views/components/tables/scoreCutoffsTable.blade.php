<div class="border border-gray-600 my-4 rounded-lg shadow-lg mx-auto p-2">
    <style>
        #scoreCutoffsTable {
            border-collapse: collapse;
            margin: auto;
        }

        #scoreCutoffsTable td, th {
            border: 1px solid black;
        }
    </style>
    <table id="scoreCutoffsTable" class="bg-gray-50">
        <tr>
            @forelse($voicePartAbbrs AS $abbr)
                <th>{{ $abbr }}</th>
            @empty
                <th>No voice parts found.</th>
            @endforelse
        </tr>
        <tr>
            @php $spacing=1; $lastScore=0; @endphp
            @forelse($scores AS $score)
                <td class="tdScoreContainer align-top">
                    <div class="scoreContainer px-1 py-1 flex flex-col space-y-0.5">

                        @forelse($score['scores'] AS $scoreArray)
                            @if($lastScore)
                                @php $spacing = abs($lastScore - $scoreArray['score']) @endphp
                            @endif
                            @php $lastScore = $scoreArray['score'] @endphp
                            @php $mt = 'mt-' . $spacing; @endphp
                            <button type="button"
                                    wire:click="clickScore({{ $scoreArray['score']}}, {{ $score['voicePartId'] }})"
                                    class="px-1 rounded-lg {{ $scoreArray['colors'] }} {{ $mt }}"
                            >
                                {{ $scoreArray['score'] }}
                            </button>
                        @empty
                            No scores found.
                        @endforelse
                    </div>
                </td>
            @empty
                <td>No scores found.</td>
            @endforelse
        </tr>
        <caption>Score Cut-Offs table</caption>
    </table>
</div>
