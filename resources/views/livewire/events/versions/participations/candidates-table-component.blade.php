<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH and RECORDS PER PAGE --}}
    {{--    <div class="flex flex-row justify-between px-4 w-full">--}}

    {{--        --}}{{--  SEARCH AND RECORDS PER ROW--}}
    {{--        @if($hasSearch || (count($rows) > 15))--}}
    {{--            @if($hasSearch)--}}
    {{--                <x-tables.searchComponent placeholder="Search name & school"/>--}}
    {{--            @else--}}
    {{--                <div></div>--}}
    {{--            @endif--}}

    {{--            --}}{{-- RECORDS PER PAGE --}}
    {{--            @if(count($rows) > 15)--}}
    {{--                <x-forms.indicators.recordsPerPage/>--}}
    {{--            @else--}}
    {{--                <div></div>--}}
    {{--            @endif--}}
    {{--        @endif--}}

    {{--    </div>--}}

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ $version->short_name . ' ' . ucwords($dto['header']) }} ({{ $rows->count() }})</div>
        </div>

        {{-- ENSEMBLE VOICE PARTS CHECK --}}
        <div class="my-1 text-red-500 text-sm w-2/3 mx-auto">
            @if(! count($ensembleVoiceParts))
                Please let your event manager know that candidate records cannot be displayed because
                event ensembles are missing or the event ensembles have no assigned voice parts.
            @endif
        </div>

        <div
            @class([
                "my-1 text-red-500 text-sm w-2/3 mx-auto rounded-lg",
                'bg-red-100 text-center' => (! $obligationAccepted)
            ])
        >
            @if(! $obligationAccepted)
                Candidate records cannot be managed until the <a href="{{ route('obligations') }}" class="font-bold">teacher
                    obligations</a> have been accepted.
            @endif
        </div>

        {{-- FILTERS and TABLE --}}
        @if($obligationAccepted)
            <div class="flex flex-row">
                @if(count($ensembleVoiceParts))
                    {{-- FILTERS --}}
                    @if($hasFilters && count($filterMethods))
                        <div class="flex justify-center">
                            <x-sidebars.filters :filters="$filters" :methods="$filterMethods"/>
                        </div>
                    @endif

                    {{-- SCROLLABLE LIST OF LINKS --}}
                    <style>
                        .eligible {
                            background-color: gray;
                            border-color: gray;
                        }

                        .engaged {
                            background-color: yellow;
                            border-color: blanchedalmond;
                        }

                        .registered {
                            background-color: mediumseagreen;
                            border-color: mediumseagreen;
                        }

                        .prohibited, .removed, .withdrew {
                            background-color: indianred;
                            border-color: indianred;
                        }

                    </style>
                    <div class="flex w-full">
                        <!-- Left side: Scrollable list of links -->
                        <div class="w-1/2 sm:w-1/4 overflow-y-auto h-screen">
                            <ul class="list-none ml-1">
                                {{--                        @for($i=0; $i<4; $i++)--}}{{-- for testing --}}
                                @foreach($rows as $row)
                                    <li>
                                        <button wire:click="selectCandidate({{ $row->candidateId }})"
                                                class="text-xs text-left block py-1 border-b hover:text-blue-500">
                                            <input type="checkbox" class="w-2 h-2 mb-0.5 {{ $row->status }}"/>
                                            {{ $row->last_name . ($row->suffix_name ? ' ' . $row->suffix_name : '') . ', ' . trim($row->first_name . ' ' . $row->middle_name) }}
                                        </button>
                                    </li>
                                @endforeach
                                {{--                        @endfor--}}
                            </ul>
                        </div>

                        <!-- Right side: form -->
                        <div class="w-full ml-2 overflow-y-auto h-screen">

                            {{-- EPAYMENT STUDENT --}}
                            <div id="Payments Checkbox"
                                @class([
                                "p-2 mb-2",
                                "bg-gray-200 border border-gray-600 rounded-lg shadow-lg" => $versionEpaymentStudent,
                                ])
                            >
                                @if($versionEpaymentStudent)
                                    <div class="">
                                        <h3>{{ $version->name }} will accept {{ $ePaymentVendor }} payments from your
                                            students.</h3>
                                        <div class="flex flex-row space-x-2 items-center">
                                            <input type="checkbox" wire:model.live="teacherEpaymentStudent"/>
                                            <label for="epayment_student">
                                                Click here to allow your students to pay through {{ $ePaymentVendor }}.
                                            </label>
                                        </div>
                                        <div class="text-xs italic text-green-600 ml-8">
                                            @if($teacherEpaymentStudent)
                                                Last Updated: {{ $teacherEpaymentStudentLastUpdated }}
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- FORM --}}
                            <div class="advisory text-center text-gray-500">
                                @if($form->firstName)
                                    <x-forms.partials.candidateForm
                                        :auditionFiles="$auditionFiles"
                                        :ensembleVoiceParts="$ensembleVoiceParts"
                                        :eventGrades="$eventGrades"
                                        :missingApplicationRequirements="$form->missingApplicationRequirements"
                                        :height="$height"
                                        :heights="$heights"
                                        :form="$form"
                                        :pathToRegistration="$pathToRegistration"
                                        :shirtSize="$shirtSize"
                                        :shirtSizes="$shirtSizes"
                                        :showRegistrationPath="$showRegistrationPath"
                                        :showSuccessIndicator="$showSuccessIndicator"
                                        :studentHomeAddress="$studentHomeAddress"
                                        successMessage="{{  $successMessage }}"
                                        :teachers="$teachers"
                                    />
                                @else
                                    Click name to complete form...
                                @endif
                            </div>
                        </div>

                    </div>
                @endif

            </div>

            {{-- SUCCESS INDICATOR --}}
            <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                                 message="{{  $successMessage }}"/>

        @endif
    </div>

</div>


