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
    @endif

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW BUTTON --}}
        <div class="flex justify-between mb-1">
            <div>{{ $program->school->name . ' ' . ucwords($dto['header']) }}</div>

            {{-- ADD-NEW BUTTON --}}
            <button
                type="button"
                wire:click="addNew"
                class="bg-green-500 text-white text-3xl px-2 rounded-lg"
                title="Add New"
                tabindex="-1"
            >
                +
            </button>
        </div>

        {{-- PROGRAM HEADER --}}
        <div id="header" class="flex flex-col border border-white border-t-gray-500 border-b-gray-500 mb-2">

            {{-- TITLE --}}
            <div class="flex flex-row space-x-2">
                <label class="w-20">Title</label>
                <div class="data font-semibold">{{ $program->title }}</div>
            </div>

            {{-- SUBTITLE --}}
            @if($program->subtitle)
                <div class="flex flex-row space-x-2">
                    <label class="w-20">Subtitle</label>
                    <div class="data font-semibold">{{ $program->subtitle }}</div>
                </div>
            @endif

            {{-- PERFORMANCE DATE --}}
            <div class="flex flex-row space-x-2">
                <label class="w-20">Perf.Date</label>
                <div class="data font-semibold">{{ $program->humanPerformanceDate }}</div>
            </div>

        </div>{{-- end of program header --}}

        {{-- ADD SELECTION FORM & DISPLAY PAGES --}}
        <div id="splitPage" class="flex flex-col space-y-4 md:flex-row md:space-y-0">

            {{-- DISPLAY PAGE --}}
            <div id="displayPage"
                 class="w-full md:w-1/2 mr-4 shadow-lg p-2 border border-gray-200 border-r-gray-100 border-b-gray-100 rounded-lg">

                {{-- PROGRAM TITLE --}}
                <div class="text-center italic ">
                    {{ $program->title }}
                </div>

                {{-- PROGRAM SUBTITLE --}}
                @if(strlen($program->subtitle))
                    <div class="text-center italic text-sm">
                        {{ $program->subtitle }}
                    </div>
                @endif

                {{-- PERFORMANCE DATE --}}
                <div class="text-center italic text-xs">
                    {{ $program->humanPerformanceDateLong }}
                </div>

                {{-- PROGRAM TABLE --}}
                <div id="programSelectionsTable">
                    {!! $selections !!}
                </div>

            </div>{{-- end of displayPage --}}

            {{-- DATA ENTRY FORM --}}
            <div id="dataEntryForm" class="w-full md:w-1/2 p-2 border border-gray-200 rounded-lg {{ $form->bgColor }}">

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
                    {{-- DISPLAY FORM IN EDIT MODE --}}

                    <x-programs.programSelectionProfile
                        artistBlock="{!! $form->programSelection->artistBlock !!}"
                        title="{{ $form->programSelection->title }}"
                        voicing="{{ $form->voicing }}"
                    />

                    @include('components.forms.elements.livewire.programComponents.addendums')

                    <x-buttons.submit
                        type="button"
                        :livewire=true
                        wireClick="updateProgramSelection"
                        value="update concert Selection"
                    />

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

                @endif

                {{-- SUCCESS INDICATOR --}}
                <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                                     message="{{  $successMessage }}"/>

            </div>{{-- end of dataEntryForm --}}

        </div>{{-- end of splitPage --}}

    </div>{{-- end of page content --}}

</div>
