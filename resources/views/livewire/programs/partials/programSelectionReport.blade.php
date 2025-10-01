<div>

    <h3 class="font-semibold my-2 px-2 bg-gray-300">
        Top 10 songs for {{ $tableFor }}
    </h3>

    <style>
        #topTenTable{
            td,th{border: 1px solid darkgray; padding: 0 0.25rem; text-align: left;}
        }
    </style>

    <table id="topTenTable" class="border-collapse">
        <thead>
        <tr>
            <th>Title</th>
            <th>Voicing</th>
            <th>Composer</th>
            <th>Arranger</th>
            <th class="text-center">Count</th>
            <th class="sr-only">View</th>
        </tr>
        </thead>
        <tbody>

    @forelse($topTen AS $item)

            <tr>
                <td>{{ $item->title }}</td>
                <td>{{ $item->voicing }}</td>
                <td>{{ $item->composer }}</td>
                <td>{{ $item->arranger }}</td>
                <td style="text-align: center">{{ $item->total }}</td>
                <td>
                    <button
                        type="button"
                        wire:click="clickViewButton({{ $item->lib_item_id }})"
                        wire:key="libItemId_{{ $item->lib_item_id }}"
                        class="px-2 text-xs rounded-lg shadow-lg bg-yellow-500"
                    >
                        View
                    </button>
                </td>
            </tr>
    @empty
        <tr>
            <td colspan="5">No songs found for this criteria...</td>
        </tr>
    @endforelse

        </tbody>
    </table>

    {{-- PROGRAMS --}}
    <div>
        @if($programs)

            <div class="bg-gray-300 mt-2 p-2 rounded-lg space-y-2">

                @foreach($programs AS $program)

                <div class="bg-gray-100 border border-gray-800 rounded-lg p-2">
                    <h4 class="mx-2 font-semibold">Program {{ $program['program']->id }} ({{ $program['program']->school_year }})</h4>
                    <h5 class="mx-4">Ensemble {{ $program['ensemble']->id }} ({{ $program['ensemble']->voicing }})</h5>
                    @forelse($program['selections'] AS $selection)
                        <div class="ml-8">
                            <div>
                                {{ $selection->title }}
                            </div>
                        </div>
                    @empty
                        <div>No program selections found</div>
                    @endforelse
                </div>

              @endforeach

            </div>

        @endif
    </div>

</div>
