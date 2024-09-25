<div class="px-4">

    {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
    <div class="flex justify-between my-2">
        <div>Timeslot Assignment Form</div>
        <div class="flex items-center space-x-2">
            <x-buttons.export/>
        </div>
    </div>

    <div id="container">
        @include('components.tables.timeslotAssignmentsTable')
    </div>

</div>
