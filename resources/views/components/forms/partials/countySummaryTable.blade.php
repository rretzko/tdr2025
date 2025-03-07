<table class="max-w-fit mx-auto my-1">
    <thead>
    <tr>
        @foreach($columnHeaders AS $columnHeader)
            <th class="w-8 text-center border border-gray-600 px-2">
                {{ $columnHeader }}
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @forelse($rows AS $row)
        <tr>
            <td
                @class([
                    "w-8 border border-gray-600 px-2",
                    'text-gray-300' => (!$row['name'])
                ])
            >
                {{ $row['name'] }}
            </td>
            <td
                @class([
                    "w-8 border border-gray-600 px-2 text-center",
                    'text-gray-300' => (!$row['obligated'])
                ])
            >
                {{ $row['obligated'] }}
            </td>
            <td
                @class([
                    "w-8 border border-gray-600 px-2 text-center",
                    'text-gray-300' => (!$row['participating'])
                ])
            >
                {{ $row['participating'] }}
            </td>
            <td
                @class([
                    "w-8 border border-gray-600 px-2 text-center",
                    'text-gray-300' => (!$row['students'])
                ])
            >
                {{ $row['students'] }}
            </td>
            <td
                @class([
                    "w-8 border border-gray-600 px-2 text-center w-fit",
                    'text-gray-300' => (!$row['regMgrName'])
                ])
            >
                {{ $row['regMgrName'] }}
            </td>
        </tr>
    @empty
        <tr>
            <td class="w-8 text-center border border-gray-600" colspan="{{ count($columnHeaders) }}">
                No counts found.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
