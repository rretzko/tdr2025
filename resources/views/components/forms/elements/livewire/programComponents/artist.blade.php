@props([
    'type',
    'results',
    'resultsName',
])
<div class="">
    <label for="{{ $type }}" class="w-full flex flex-row ">
        <div class="w-36 text-right mr-1 pt-2">
            @if($type === 'wam')
                Words and Music
            @else
                {{ ucwords($type) }}
            @endif
        </div>
        <div class="flex flex-col">
            <input
                type="text"
                wire:model.live.debounce.500ms="form.{{ $type }}"
                class="w-fit"
            />
            <x-forms.elements.livewire.programComponents.artistResults
                type="{{ $type }}"
                resultsName="{{ $resultsName }}"
                :results="$results"
            />
        </div>
    </label>
</div>
