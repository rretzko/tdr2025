<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH --}}
    @if($hasSearch)
        <div class="px-4 w-11/12 ">
            <div class="flex flex-col">
                <div class="flex flex-row w-full space-x-2 ">
                    <input wire:model="search"
                           class="w-3/4"
                           type="text"
                           placeholder="Search by program title, school year, tag, and song title"
                           aria-label="Search"
                    />
                    <button
                        wire:click="updateSearchCriteria"
                        type="button"
                        class="bg-black text-white px-2 rounded-lg"
                    >
                        Search
                    </button>
                </div>
                <div id="hint" class="text-xs italic">
                    Place multi-word song title between double-quotes (ex: "Battle Hymn of the Republic")
                </div>
            </div>
        </div>
    @endif {{-- end of if(hasSearch) --}}

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        @include('components.forms.elements.livewire.programComponents.programViewHeader')

        {{-- ADD SELECTION FORM & DISPLAY PAGES --}}
        <div id="splitPage" class="flex flex-col space-y-4 md:flex-row md:space-y-0">

            @include('components.forms.elements.livewire.programComponents.programDefaultDisplay')

            @if($displayEnsembleStudentRoster)

                @include('components.forms.elements.livewire.programComponents.displayEnsembleStudentRoster')

            @elseif($displayNewStudentMemberForm)

                @include('components.forms.elements.livewire.programComponents.newStudentMemberForm')

            @elseif($displayUploadStudentMembersForm)

                @include('components.forms.elements.livewire.programComponents.uploadStudentMembersForm')

            @else

                {{-- DATA ENTRY FORM --}}
                <div id="dataEntryForm"
                     class="w-full md:w-1/2 p-2 border border-gray-200 rounded-lg {{ $form->bgColor }}">

                    <div>
                        {!! $form->headerText !!}
                    </div>

                    {{-- ENSEMBLE SELECTION --}}
                    <div>
                        <label>Select an ensemble</label>
                        @if(count($ensembles))
                            <div class="flex flex-col">
                                <select wire:model="form.ensembleId" autofocus>
                                    @foreach($ensembles AS $key => $ensembleName)
                                        <option value="{{ $key }}">
                                            {{ $ensembleName }}
                                        </option>
                                    @endforeach
                                </select>
                                <label class="">or add a new ensemble.</label>
                            </div>
                        @endif
                        <x-forms.elements.livewire.programComponents.ensembleName/>
                        <div class="bg-red-100 text-red-900 w-fit px-2 text-sm italic mt-1 rounded-lg">
                            {!! $this->ensembleNameError !!}
                        </div>

                    </div>

                    {{-- PROGRAM ORDER --}}
                    <div class="flex flex-col">
                        <label>Performance Order</label>
                        <input type="text"
                               wire:model="form.performanceOrderBy"
                               class="w-12"
                        >
                    </div>

                    @if($form->programSelectionId)

                        @include('components.forms.elements.livewire.programComponents.editProgramSelection')

                    @else
                        {{-- DISPLAY FORM IN ADD MODE --}}

                        {{-- SELECTION TITLE --}}
                        <div>
                            <label>Selection Title</label>
                            <x-forms.elements.livewire.programComponents.selectionTitle/>
                            @if($resultsSelectionTitle)
                                <div id="selectionTitleResults" class="flex flex-col ml-2 mt-1 text-xs">
                                    @foreach($resultsSelectionTitle AS $libItem)
                                        <button
                                            type="button"
                                            wire:click="clickTitle({{ $libItem->id }})"
                                            @class([
                                                "text-left text-blue-500 p-1 rounded-lg mb-0.5",
                                                'bg-gray-200 border border-gray-300' => $loop->even,
                                                'border border-gray-300' => $loop->odd
                                                ])
                                            wire:key="libItemId_{{ $libItem->id }}"
                                        >
                                            <div>
                                                {!! $libItem->longLink() !!}
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @endif

                        </div>

                        {{-- ARTISTS --}}
                        @foreach($artistTypes AS $type)
                            <div wire:key="{{ $type }}">
                                <x-forms.elements.livewire.programComponents.artist
                                    type="{{ $type }}"
                                    resultsName="results{{ ucwords($type) }}"
                                    :results="$resultsComposer"
                                />
                            </div>
                        @endforeach

                        {{-- ADDENDUM --}}
                        <div id="addendums" class="flex flex-col mt-2 border border-gray-100 border-t-gray-300">
                            @include('components.forms.elements.livewire.programComponents.addendums')
                        </div>

                        <x-buttons.submit
                            type="button"
                            :livewire=true
                            wireClick="addConcertSelection"
                            value="add concert Selection"
                        />

                    @endif

                    {{-- SUCCESS INDICATOR --}}
                    <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                                         message="{{  $successMessage }}"/>

                </div>{{-- end of dataEntryForm --}}

            @endif {{-- end of if(displayEnsembleStudentRoster) }}

        </div>{{-- end of splitPage --}}

    </div>{{-- end of page content --}}

</div>
