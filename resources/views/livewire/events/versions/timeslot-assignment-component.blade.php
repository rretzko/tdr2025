<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">

        @include('components.forms.partials.timeslotConfigurationsForm')

        <div class="bg-gray-100 px-2 rounded-lg my-2">
            <div class="flex flex-row justify-between">
                <div>Summary Table</div>
                <div>Show/Hide</div>
            </div>
        </div>

        @if($startTime && $endTime && $duration)
            @include('components.forms.partials.timeslotAssignmentForm')
        @endif

    </div>{{-- END OF ID=CONTAINER --}}

</div>


