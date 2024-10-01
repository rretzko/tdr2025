<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">

        <x-tables.roomBackupsTable
            :columnHeaders="$columnHeaders"
            header="{{ $dto['header'] }}"
            :rows="$rows"
            :sortAsc="$sortAsc"
            sortColLabel="{{ $sortColLabel }}"
        />

    </div>{{-- END OF ID=CONTAINER --}}

</div>


