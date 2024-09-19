<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            {{--            <div class="flex items-center space-x-2">--}}
            {{--                <button type="button"--}}
            {{--                        wire:click="add()"--}}
            {{--                        class="bg-green-500 text-white text-3xl px-2 rounded-lg"--}}
            {{--                        title="Add New Room"--}}
            {{--                        tabindex="-1"--}}
            {{--                >--}}
            {{--                    +--}}
            {{--                </button>--}}
            {{--                <x-buttons.export/>--}}
            {{--            </div>--}}
        </div>

        <x-tables.roomBackupsTable
            :columnHeaders="$columnHeaders"
            header="{{ $dto['header'] }}"
            :rows="$rows"
            :sortAsc="$sortAsc"
            sortColLabel="{{ $sortColLabel }}"
        />

    </div>{{-- END OF ID=CONTAINER --}}

</div>


