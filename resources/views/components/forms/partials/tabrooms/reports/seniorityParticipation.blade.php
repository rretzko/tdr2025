<div class="space-y-4 mx-4 p-2 border border-gray-200 ">

    @if($eventEnsembleCount > 1)
        <div class="mx-4 flex flex-col md:flex-row md:space-x-4">
            @foreach($eventEnsembles AS $eventEnsemble)
                <div class="items-center w-fit md:w-full">
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
            <caption>Student Count: {{ count($seniorityParticipation) }}</caption>
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
                    year count
                </th>
                @foreach($versionSeniorYears AS $seniorYear)
                    <th>
                        {{ $seniorYear }}
                    </th>
                @endforeach

            </tr>
            </thead>
            <tbody>
            @forelse($seniorityParticipation AS $row)
                <tr class="hover:bg-green-100">
                    <td>
                        <div class="font-semibold">
                            {{ $row->programName }}
                        </div>
                        <div class="text-xs ml-2">
                            ({{ $row->class_of }})
                        </div>
                    </td>
                    <td>
                        @php
                            $chs = str_replace('Central High School', 'CHS', $row->schoolName);
                            $rhs = str_replace('Regional High School', 'RHS', $chs);
                            $hs = str_replace('High School', 'HS', $rhs);
                        @endphp
                        <div>{{ $hs }}</div>
                        <div class="ml-2">{{ $row->teacherName }}</div>
                    </td>
                    <td style="text-align: center;">
                        {{ $row->voicePartAbbr }}
                    </td>
                    <td style="text-align: center;">
                        {{ $row->countYears }}
                    </td>
                    @foreach($versionSeniorYears AS $key => $year)
                        <td style="text-align: center;"
                            class="{{ (strlen($row->years[$key]) ? 'bg-green-100' : 'bg-red-100') }}"
                        >
                            {{ $row->years[$key]  }}

                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td style="text-align: center;" colspan="{{ 4 + count($versionSeniorYears) }}">
                        No participants found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
