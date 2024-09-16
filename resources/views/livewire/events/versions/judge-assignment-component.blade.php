<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            <div class="flex items-center space-x-2">
                <button type="button"
                        wire:click="$toggle('showForm')"
                        class="bg-green-500 text-white text-3xl px-2 rounded-lg"
                        title="Add New Room"
                        tabindex="-1"
                >
                    +
                </button>
                <x-buttons.export/>
            </div>
        </div>

        @if($showForm)
            <div class="bg-gray-200 mx-2 my-2 px-2 py-2 rounded-lg shadow-lg w-full">
                @include('components.forms.partials.roomForm')
                @if($form->sysId)
                    <hr class="h-1 bg-gray-300"/>
                    @include('components.forms.partials.judgeForm')
                @endif
            </div>
        @endif

        <x-tables.roomsTable
            :columnHeaders="$columnHeaders"
            header="{{ $dto['header'] }}"
            :rows="$rows"
            :roomJudges="$roomJudges"
            :roomScoreCategories="$roomScoreCategories"
            :sortAsc="$sortAsc"
            sortColLabel="{{ $sortColLabel }}"
            :roomVoiceParts="$roomVoiceParts"
        />

    </div>{{-- END OF ID=CONTAINER --}}

</div>

