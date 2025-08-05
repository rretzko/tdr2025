@props([
    'artistName',
    'canEdit',
    'for',
    'hint' => '',
    'placeholder' => 'full name',
    'searchResults' => '',
])
<div class="flex flex-col sm:flex-row sm:space-x-2 items-center ">
    <label for="{{ $for }}" class="sm:w-1/12">
        {{ ucwords($for) }}
    </label>
    <div class="flex flex-col sm:w-5/6 ">
        @if($canEdit)
            <input
                type="text"
                wire:model.live.debounce="form.artists.{{ $for }}"
                class="w-5/6"
                id="{{ $for }}"
                placeholder="{{ ucwords($placeholder) }}"
            />

            {{-- hint --}}
            @if($hint)
                <hint class="text-sm italic">
                    {{ $hint }}
                </hint>
            @endif

            {{-- hint --}}
            @if($for === 'wam')
                <hint class="text-sm italic">
                    Words and Music
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
        @else
            <div class="flex flex-row space-x-2">
                <div>
                    {{ $artistName }}
                </div>
                <div class=" text-blue-800 pt-1"
                     title="Artists are editable ONLY IF:
                    (1) you have created the artist,
                    (2) the artist is referenced on one and only one library item anywhere on the system, and
                    (3) the library item has not been verified."
                >
                    <x-heroicons.question/>
                </div>
            </div>
        @endif

    </div>

</div>
