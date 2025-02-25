<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>Add or Edit {{ ucwords($dto['header']) }}</div>
            <div class="flex items-center space-x-2">
                <button type="button"
                        wire:click="addCoregistrationManager()"
                        class="bg-green-500 text-white text-3xl px-2 rounded-lg"
                        title="Add New Co-registration Manager"
                        tabindex="-1"
                >
                    +
                </button>
                <x-buttons.export/>
            </div>
        </div>


        {{-- ERROR MESSAGES --}}
        @if($errors->all())
            <div class="bg-red-200 text-red-950 ml-2 px-2 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $key => $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- SUCCESS MESSAGE --}}
        <div class="bg-green-200 text-green-950 ml-2 px-2 rounded-lg">
            {{ $successMessage }}
        </div>

        {{-- ADD/EDIT FORM --}}
        @if($showForm)
            <div class="bg-gray-200 mx-2 my-2 px-2 py-2 rounded-lg shadow-lg w-full">
                @include('components.forms.partials.registrationManagers.coregistrationManagerForm')
            </div>
        @endif

        {{-- COREGISTRATION MANAGER TABLE --}}
        @include('components.tables.coregistrationManagersTable')



        {{--        <x-tables.roomsTable--}}
        {{--            :columnHeaders="$columnHeaders"--}}
        {{--            header="{{ $dto['header'] }}"--}}
        {{--            :rows="$rows"--}}
        {{--            :roomJudges="$roomJudges"--}}
        {{--            :roomScoreCategories="$roomScoreCategories"--}}
        {{--            :sortAsc="$sortAsc"--}}
        {{--            sortColLabel="{{ $sortColLabel }}"--}}
        {{--            :roomVoiceParts="$roomVoiceParts"--}}
        {{--            :showSuccessIndicator="$showSuccessIndicator"--}}
        {{--            successMessage="{{ $successMessage }}"--}}
        {{--        />--}}

    </div>{{-- END OF ID=CONTAINER --}}

</div>


