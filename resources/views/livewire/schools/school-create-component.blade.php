<div class="px-4">
    <h2>{{ $header }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

        <div class="space-y-4">
            <x-forms.styles.genericStyle/>

            {{-- SYS ID --}}
            <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" data="new"/>

            {{-- POSTAL CODE --}}
            <x-forms.elements.livewire.inputTextNarrow label="zip code"
                                                       name="postalCode"
                                                       required
                                                       autofocus
                                                       :results="$resultsPostalCode"
            />

            {{-- SCHOOL NAME --}}
            <x-forms.elements.livewire.inputTextWide label="school name"
                                                     name="name"
                                                     placeholder="Enter full name without abbreviations"
                                                     required
                                                     :results="$resultsName"
            />

            {{-- CITY --}}
            <x-forms.elements.livewire.inputTextWide label="city"
                                                     name="city"
                                                     placeholder=""
                                                     required
                                                     :results="$resultsCity"
            />

            {{-- COUNTY --}}
            <x-forms.elements.livewire.selectNarrow advisory="{{{  $advisoryCountyId }}}"
                                                    label="county"
                                                    name="countyId"
                                                    option0
                                                    :options="$counties"
                                                    required

            />

            {{-- GRADES TAUGHT IN SCHOOL --}}
            <div class="flex flex-col">
                <label for="" class="required">Grades Taught in School</label>
                <div class="flex flex-row space-x-2">
                    @for($i=1; $i<13; $i++)
                        <div>
                            <input wire:model="gradesTaught"
                                   wire:key="gradesTaught{{ $i }}"
                                   type="checkbox"
                                   value="{{ $i }}"
                            />
                            <label>{{ $i }}</label>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- GRADES I TEACH IN SCHOOL --}}
            <div class="flex flex-col">
                <label for="" class="required">Grades I Teach in School</label>
                <div class="flex flex-row space-x-2">
                    @for($i=1; $i<13; $i++)
                        <div>
                            <input wire:model="gradesITeach"
                                   wire:key="gradesITeach{{ $i }}"
                                   type="checkbox"
                                   value="{{ $i }}"
                            />
                            <label>{{ $i }}</label>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- WORK EMAIL --}}
            <div class="flex flex-col">
                <x-forms.elements.livewire.inputTextWide label="work email"
                                                         name="email"
                                                         required
                />
                @if($emailVerified)
                    <div class="mt-2 text-sm text-green-600">Verified</div>
                @else
                    <div class="mt-2 text-sm text-red-600">Unverified</div>
                @endif
            </div>

        </div>

        {{-- SUBMIT --}}
        <div class="flex flex-col mt-2 max-w-xs">
            <button type="submit"
                    class="bg-gray-800 text-white px-2 rounded-full disabled:cursor-not-allowed disabled:opacity-50"
            >
                Submit
            </button>
        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>

    </form>
</div>
