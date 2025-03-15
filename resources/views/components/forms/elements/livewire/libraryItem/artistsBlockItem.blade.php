@props([
    'for',
    'hint' => '',
    'placeholder' => 'full name',
    'searchResults' => '',
])
<div class="flex flex-col sm:flex-row sm:space-x-2 items-center ">
    <label for="{{ $for }}" class="sm:w-1/6">
        {{ ucwords($for) }}
    </label>
    <div class="flex flex-col sm:w-5/6 ">
        <input
            type="text"
            wire:model.live.debounce="form.artists.{{ $for }}"
            class="w-11/12"
            id="{{ $for }}"
            placeholder="{{ ucwords($placeholder) }}"
        />

        {{-- hint --}}
        @if($hint)
            <hint class="text-sm italic">
                {{ $hint }}
            </hint>
        @endif

        {{-- search results --}}
        @if($searchResults)
            <div class='flex flex-col items-start ml-4 text-sm list-none cursor-pointer'>
                @foreach($searchResults AS $artist)
                    <button type='button' wire:click="setArtist('{{ $artist['type'] }}', {{$artist['id'] }})"
                            class='text-blue-800 hover:underline '>
                        {{ $artist['name'] }}
                    </button>
                @endforeach
            </div>
        @endif

    </div>
</div>
