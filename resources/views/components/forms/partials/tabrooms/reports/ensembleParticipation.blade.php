<div class="space-y-4 mx-4 p-2 border border-gray-200 ">

    @if($eventEnsembleCount > 1)
        <div class="mx-4 flex flex-col md:flex-row md:justify-start md:space-x-4">
            @foreach($eventEnsembles AS $eventEnsemble)
                <div class="items-center">
                    <input type="radio" wire:model.live="eventEnsembleId" value="{{ $eventEnsemble->id }}">
                    <label>{{ $eventEnsemble->ensemble_name }}</label>
                </div>
            @endforeach
        </div>
    @endif

    <div>
        <style>
            #participants {
                border-collapse: collapse;
                margin: auto;
            }

            #participants td, th {
                border: 1px solid black;
                padding: 0 0.25rem;
                text-align: left;
            }
        </style>

        {{-- PRINTER --}}
        <div class="flex justify-end mr-8 border border-white border-t-gray-200 p-2 text-blue-500">
            <button type="button" wire:click="export">
                @include('components.heroicons.tableCells')
            </button>
        </div>

        {{-- DATA TABLE --}}
        <table id="participants">
            <caption>Student Count: {{ count($participants) }}</caption>
            <thead>
            <tr>
                <th>
                    name
                </th>
                <th>
                    school
                </th>
                <th>
                    vp
                </th>
                <th>
                    score
                </th>
                <th>
                    emergency contact
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($participants AS $row)
                <tr class="hover:bg-green-100">
                    <td>
                        <div class="font-semibold">{{ $row->programName }}</div>
                        <div class="ml-2">{{ $row->email }}</div>
                        <div class="ml-2">{{ $row->phoneMobile }} (c)</div>
                        <div class="ml-2">{{ $row->phoneHome }} (h)</div>
                    </td>
                    <td>
                        <div class="font-semibold">{{ $row->schoolName }}</div>
                        <div class="ml-2">{{ $row->teacherName }}</div>
                        <div class="ml-2">{{ $row->teacherEmail }}</div>
                        <div class="ml-2">{{ $row->phoneMobileT }} (c)</div>
                        <div class="ml-2">{{ $row->phoneWorkT }} (w)</div>
                    </td>
                    <td style="text-align: center;">
                        {{ $row->voicePartAbbr }}
                    </td>
                    <td style="text-align: center;">
                        {{ $row->total }}
                    </td>
                    <td>
                        @if(strlen($row->EcName))
                            <div>{{ $row->EcName }}</div>
                            <div>
                                {{ strlen($row->EcEmail) ? $row->EcEmail : 'No email found' }}
                            </div>
                            @if(strlen($row->phoneMobileEC))
                                <div>{{ $row->phoneMobileEC }} (c)</div>
                            @endif
                            @if(strlen($row->phoneHomeEC))
                                <div>{{ $row->phoneHomeEC }} (h)</div>
                            @endif
                            @if(strlen($row->phoneWorkEC))
                                <div>{{ $row->phoneWorkEC }} (w)</div>
                            @endif
                        @else
                            <div>None found.</div>
                        @endif

                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>

</div>
