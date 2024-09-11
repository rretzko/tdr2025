<table class="max-w-fit mx-auto my-1">
    <thead>
    <tr>
        @foreach($summaryColumnHeaders AS $summaryHeader)
            <th class="w-8 text-center border border-gray-600">
                {{ $summaryHeader }}
            </th>
        @endforeach
        <th class="w-8 text-center border border-gray-600">
            Total
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        @forelse($summaryCounts AS $count)
            <td
                @class([
                    "w-8 text-center border border-gray-600",
                    'text-gray-300' => (!$count)
                ])
            >
                {{ $count }}
            </td>
        @empty
            <td class="w-8 text-center border border-gray-600" colspan="{{ count($summaryColumnHeaders) }}">
                No counts found.
            </td>
        @endforelse
        <th class="w-8 text-center border border-gray-600">
            {{ array_sum($summaryCounts) }}
        </th>
    </tr>
    </tbody>
</table>
