<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH and RECORDS PER PAGE --}}
    <div class="flex flex-row justify-between px-4 w-full">

        {{-- SEARCH --}}
        <x-tables.searchComponent/>

        {{-- RECORDS PER PAGE --}}
        <x-forms.indicators.recordsPerPage/>

    </div>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            <div class="flex items-center space-x-2">
                {{--                <x-buttons.addNew route="student.create"/>--}}
                <x-buttons.export/>
            </div>
        </div>

        {{-- FILTERS and TABLE --}}
        <div class="flex flex-row ">

            {{--             FILTERS--}}
            @if($hasFilters && count($filterMethods))
                <div class="flex justify-center">
                    <x-sidebars.filters :filters="$filters" :methods="$filterMethods"/>
                </div>
            @endif

            {{-- PAYMENT FORM AND TABLE WITH LINKS--}}
            <div class="flex flex-col space-y-2 mb-2 w-full">

                {{-- PAYMENT FORM --}}
                <div
                    @class([
                    '',
                    'block' => $showPaymentForm,
                    'hidden' => (! $showPaymentForm)
                    ])
                >
                    @include('components.forms.partials.teacherPaymentForm')
                </div>


                <x-links.linkTop :recordsPerPage="$recordsPerPage" :rows="$rows"/>

                {{-- REGISTRATION MANAGER SELECTORS --}}
                @if($multipleRegistrationManagers)
                    <div id="registrationManagerSelectors"
                         class="my-2 border border-gray-300 border-r-white border-l-white w-full px-2 flex flex-row justify-around shadow-lg">
                        @foreach($registrationManagers AS $manager)
                            <button wire:click="updateActiveRegistrationManager('{{$manager['id']}}')"
                                @class([
                                    'text-gray-400'=> ($manager['id'] != $activeRegistrationManager),
                                    'text-blue-500 font-bold' => ($manager['id'] == $activeRegistrationManager),
                                ])
                            >
                                {{ $manager['name'] }}
                            </button>
                        @endforeach
                    </div>
                @endif

                {{-- TABLE--}}
                <x-tables.participatingSchoolsTable
                    :columnHeaders="$columnHeaders"
                    :header="$dto['header']"
                    :payments="$payments"
                    :paymentsDue="$paymentsDue"
                    :paymentsStatus="$paymentsStatus"
                    :recordsPerPage="$recordsPerPage"
                    :rows="$rows"
                    :showPaymentForm="$showPaymentForm"
                    :sortAsc="$sortAsc"
                    :sortColLabel="$sortColLabel"
                />

                {{--                 LINKS:BOTTOM--}}
                <x-links.linkBottom :rows="$rows"/>

            </div>

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>

</div>



