<div class="bg-gray-100 px-2 rounded-lg my-2 pb-2">

    {{-- SECTION HEADER --}}
    <div class="flex flex-row justify-between">
        <div>Summary Table</div>
        <button type="button" wire:click="$toggle('showSummaryTable')"
            @class([
                'text-green-500 cursor-pointer',
                'text-red-500' => $showSummaryTable,
            ])
        >
            {{ $showSummaryTable ? 'Hide...' : 'Show...'}}
        </button>
    </div>

    {{-- TABLE BODY --}}
    @if($showSummaryTable)
        <style>
            #summaryTable td, th {
                border: 1px solid darkgray;
                padding: 0 0.25rem;
            }
        </style>
        <table id="summaryTable" class="mx-auto w-5/6">
            <thead>
            <tr>
                <th>timeslot</th>
                <th>schools#</th>
                @foreach($voicePartHeaders AS $voicePartHeader)
                    <th>{{ $voicePartHeader['label'] }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @forelse($summary AS $summaryRow)
                <tr>
                    @foreach($summaryRow AS $detail)
                        @if(is_array($detail))
                            @foreach($detail AS $item)
                                <td
                                    @class([
                                        "text-center",
                                        'text-gray-300' => ($item === 0),
                                    ])
                                >
                                    {{ $item }}
                                </td>
                            @endforeach
                        @else
                            <td @class([
                                        "text-center",
                                        'text-gray-300' => ($detail === 0),
                                    ])
                            >
                                {{ $detail }}
                            </td>
                        @endif
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">
                        No time assignments found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endif
</div>
