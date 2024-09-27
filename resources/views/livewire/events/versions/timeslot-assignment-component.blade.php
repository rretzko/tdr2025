<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">

        @include('components.forms.partials.timeslotConfigurationsForm')

        @include('components.tables.timeslotSummaryTable')

        @if($startTime && $endTime && $duration)
            @include('components.forms.partials.timeslotAssignmentForm')
        @endif

    </div>{{-- END OF ID=CONTAINER --}}

</div>


