<div>
    <style>
        #candidateStatusTable {
            width: 90%;
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
            <th title="recordings">
                <div class="flex items-center justify-center">
                    <x-heroicons.microphone/>
                </div>
            </th>
        </tr>
        </thead>
        <tbody>
        @forelse($rows AS $row)
            <tr>
                <td class="{{ $row->status }}">

                </td>
                <td class="text-left">
                    {{ $row->program_name }}
                </td>
                <td class="text-center">
                    {{ $row->voicePart }}
                </td>
                <td>
                    <div @class([
                         'flex items-center justify-center text-red-500'
                         ])
                    >
                        <x-heroicons.bellSlash/>{{-- alt = bellAlert --}}
                    </div>
                </td>
                <td>
                    {{-- alts = document v. documentCheck --}}
                </td>
                <td>
                    {{-- alts = microphone v.  --}}
                </td>
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
