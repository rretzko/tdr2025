@props([
    'type',
    'results' => [],
    'resultsName',
])
<div id="{{ $resultsName }}" class="flex flex-col ml-2 mt-1 text-xs">
    @foreach($results AS $key=>$name)
        <button
            type="button"
            wire:click="clickArtist({{ $type }}, {{ $key }})"
            class="text-left text-blue-500 border-gray-200"
            wire:key="{{ $type }}_{{ $key }}"
        >
            <div>{{ $name }}</div>
        </button>
    @endforeach
</div>
