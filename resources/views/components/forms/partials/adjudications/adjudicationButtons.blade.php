<div>

    {{-- HEADER --}}
    <div class="flex flex-row justify-between">
        <label>Candidates ({{ count($rows) }})</label>
        <div wire:click="$toggle('showAllButtons')" class="font-semibold text-green-600 cursor-pointer">
            @if($showAllButtons)
                Incomplete...
            @else
                All...
            @endif
        </div>
    </div>

    {{-- ADJUDICATION BUTTONS --}}
    <div
        class="flex flex-row flex-wrap rounded-lg w-full text-black space-x-2 space-y-1 items-center justify-start font-mono">

        @forelse($rows AS $button)
            <button wire:click=clickRef({{ $button->id }})
                    class="w-fit border border-gray-600 rounded-lg px-2 text-xs sm:text-sm">
                {{ $button->ref }}
            </button>
        @empty
            <div class="text-black">
                No candidates for adjudication found.
            </div>
        @endforelse

    </div>

</div>
