<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container" class="space-y-2">

        <div class="flex justify-center mt-4">
            <button
                type="button"
                wire:click="clickButton"
                class="bg-black text-gray-100 text-lg p-4 rounded-lg shadow-lg"
            >
                {{ $buttonLabel }}
            </button>
        </div>

        @if($auditionCloseDateTime)
            <div class="text-sm text-green-600 flex justify-center">
                Auditions were closed on: {{ $auditionCloseDateTime }}.
            </div>
        @endif
    </div>
</div>
