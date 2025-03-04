<div class="relative overflow-x-auto">

    <table class="px-4 shadow-lg w-full">
        <thead>
        <tr>
            <th>###</th>
            <th>Name</th>
            <th>Counties</th>
            <th class="border border-transparent px-1 sr-only">
                edit
            </th>
            <th class="border border-gray-200 px-1 sr-only">
                remove
            </th>
        </tr>
        </thead>
        <tbody>

        @forelse($rows AS $row)

            <tr class="odd:bg-green-50">
                <td class="border border-gray-200 px-1 text-center">
                    {{ $loop->iteration }}
                </td>
                <td class="border border-gray-200 px-1">
                    {{ $row->alphaName }}
                </td>
                <td class="border border-gray-200 px-1">
                    {{ $row->countyNames }}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    <button wire:click="edit({{ $row->versionParticipantId }})"
                            type="button"
                            class="bg-indigo-600 text-white text-xs px-2 rounded-full hover:bg-indigo-700"
                    >
                        Edit
                    </button>
                </td>
                <td class="text-center border border-gray-200 text-center">
                    <button type="button"
                            wire:click="remove({{ $row->versionParticipantId }})"
                            wire:confirm="Are you sure you want to remove this co-registration manager's county assignments?"
                            class="bg-red-600 text-white text-xs px-2 rounded-full hover:bg-red-700"
                    >
                        Remove
                    </button>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="5" class="border border-gray-200 text-center">
                    No {{ $header }} found.
                </td>
            </tr>
        @endforelse
        </tbody>

    </table>

    {{-- LOADING COMPONENT AND SPINNER --}}
    <x-tables.loadingComponentAndSpinner/>

</div>
