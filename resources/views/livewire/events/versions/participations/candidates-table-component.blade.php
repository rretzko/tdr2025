<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

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
                        @include('components.selectors.candidateSelectors')

                        <!-- Right side: form -->
                        <div class="w-full ml-2 overflow-y-auto h-screen">

                            {{-- EPAYMENT STUDENT --}}
                            @include('components.forms.partials.candidates.studentEpaymentOption')

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
                                    <div>Click name to display form...</div>
                                    @include('components.tables.candidateSummaryStatusTable')
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


