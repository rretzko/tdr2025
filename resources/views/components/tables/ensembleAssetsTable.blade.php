@props([
    'columnHeaders',
    'rows',
])
<table class="px-4 shadow-lg w-full min-w-72">
    <thead>
    <tr>
        @foreach($columnHeaders AS $columnHeader)
            <th
                class="border border-gray-200 px-1 @if($columnHeader === 'active?') text-blue-500 @endif"
                title="@if($columnHeader === 'active?') Click to change... @endif"
            >
                {{ $columnHeader }}
            </th>
        @endforeach
        <th class="border border-transparent px-1 sr-only">
            edit
        </th>
        <th class="border border-gray-200 px-1 sr-only">
            remove
        </th>
    </tr>
    </thead>
    <tbody>

    @forelse($rows AS $asset)
        {{-- id, name, user_id --}}

        <tr class=" odd:bg-green-100 ">
            <td class="border border-gray-200 px-1">
                <div>{{ $asset['name'] }}</div> {{-- asset name --}}
            </td>
            <td class="text-center border border-gray-200">
                @if($asset['user_id'] == auth()->id())
                    <button wire:click="editAsset({{ $asset['id'] }})"
                            type="button"
                            class="bg-indigo-600 text-white text-xs px-2 rounded-full hover:bg-indigo-700"
                    >
                        Edit
                    </button>
                @endif
            </td>
            <td class="text-center border border-gray-200">
                @if($asset['user_id'] == auth()->id())
                    <button type="button"
                            wire:click="remove({{ $asset['id'] }})"
                            wire:confirm="Are you sure you want to remove this asset? This will delete the asset from ALL ensembles to which it is assigned and ALL student asset assignment records to which it is assigned."
                            class="bg-red-600 text-white text-xs px-2 rounded-full hover:bg-red-700"
                    >
                        Remove
                    </button>
                    {{--                <x-buttons.remove id="{{ $asset['id'] }}" route="asset.edit" />--}}
                @endif
            </td>
        </tr>
    @empty
        <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
            No {{ $dto['header'] }} found.
        </td>
    @endforelse
    </tbody>
</table>
