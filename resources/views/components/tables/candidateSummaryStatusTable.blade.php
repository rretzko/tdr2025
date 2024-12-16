<div>
    <style>
        #candidateStatusTable {
            max-width: 90%;
            margin: auto;
            border-collapse: collapse;
            color: black;
        }

        #candidateStatusTable td, th {
            padding: 0 0.25rem;
            border: 1px solid black;
        }

        #candidateStatusTable td.eligible {
            background-color: gray;
        }

        #candidateStatusTable td.engaged {
            background-color: yellow;
        }

        #candidateStatusTable td.registered {
            background-color: green;
        }

    </style>
    <table id="candidateStatusTable">
        <thead>
        <tr>
            <th title="status" class="w-4">
                <div class="flex items-center justify-center">
                    <x-heroicons.check/>
                </div>
            </th>
            <th>name</th>
            <th title="voice part">
                <div class="flex items-center justify-center">
                    <x-heroicons.sixteenthNotes/>
                </div>
            </th>
            <th title="emergency contact">
                <div class="flex items-center justify-center">
                    <x-heroicons.bellAlert/>
                </div>
            </th>
            <th title="signatures">
                <div class="flex items-center justify-center">
                    <x-heroicons.pencilSquare/>
                </div>
            </th>
            @if(count($summaryTable[0]['recordings']))
                <th title="recordings">
                    <div class="flex items-center justify-center">
                        <x-heroicons.microphone/>
                    </div>
                </th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse($summaryTable AS $row)
            <tr class="hover:bg-green-100">
                <td @class([
                        $row['status'], "text-sm",
                        "hover:text-gray-200" => $row['status'] === 'eligible',
                        'text-white' => $row['status'] === 'registered',
                    ])
                    title="{{ $row['status'] }}"
                >
                    @if($row['status'] === 'eligible')
                        elig
                    @elseif($row['status'] === 'engaged')
                        engd
                    @elseif($row['status'] === 'registered')
                        reg
                    @else
                        err
                    @endif
                </td>

                <td class="text-left">
                    <div wire:click="selectCandidate({{ $row['candidateId'] }})" class="text-blue-500 cursor-pointer">
                        {{ $row['programName'] }}
                    </div>
                </td>

                <td class="text-center">
                    {{ $row['voicePartAbbr'] }}
                </td>

                <td>
                    <div @class([
                         'flex items-center justify-center',
                         'text-red-500' => ((! $row['emergencyContactId']) || ($row['emergencyContactId'] == 0)),
                         'text-green-500' => ($row['emergencyContactId'] && ($row['emergencyContactId'] != 0))
                         ])
                    >
                        @if($row['emergencyContactId'] && ($row['emergencyContactId'] != 0))
                            <div
                                title="{{ $row['emergencyContactName'] }} | {{ $row['emergencyContactPhoneMobile'] }} | {{ $row['emergencyContactEmail'] }}">
                                <x-heroicons.bellAlert/>
                            </div>
                        @else
                            <x-heroicons.bellSlash/>
                        @endif
                    </div>
                </td>

                <td>
                    <div @class([
                         'flex items-center justify-center',
                         'text-red-500' => (! $row['hasSignature']),
                         'text-green-500' => $row['hasSignature'],
                         ])
                    >
                        @if($row['hasSignature'])
                            <x-heroicons.documentCheck/>
                        @else
                            <x-heroicons.document/>
                        @endif
                    </div>

                </td>
                @if(count($row['recordings']))
                    <td>
                        <div class="flex flex-row">
                            @foreach($row['recordings'] AS $recording)
                                <div
                                    title="{{ $recording['uploadType'] }} | {{ $recording['uploaded'] ? 'uploaded' : 'not uploaded' }} | {{ $recording['approved'] ? 'approved' : 'not approved' }}"
                                    @class([
                                        'mr-1',
                                        'text-red-500' => ((! $recording['uploaded']) && (! $recording['approved'])),
                                        'text-yellow-500' => (($recording['uploaded'] && (! $recording['approved'])) || ((! $recording['uploaded']) && $recording['approved'])),
                                        'text-green-500' => ($recording['uploaded'] && $recording['approved'])
                                    ])
                                >
                                    <x-heroicons.microphone/>
                                </div>
                            @endforeach
                        </div>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="6">
                    No candidates found.
                </td>
            </tr>
        @endforelse
        </tbody>

    </table>
</div>
