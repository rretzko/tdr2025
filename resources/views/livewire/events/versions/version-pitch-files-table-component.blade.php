<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH and RECORDS PER PAGE --}}
    <div class="flex flex-row justify-between px-4 w-full">

        {{--  SEARCH AND RECORDS PER ROW--}}
        @if($hasSearch || ($rows->total() > 15))
            @if($hasSearch)
                <x-tables.searchComponent placeholder="Search name & school"/>
            @else
                <div></div>
            @endif

            {{-- RECORDS PER PAGE --}}
            @if($rows->total() > 15)
                <x-forms.indicators.recordsPerPage/>
            @else
                <div></div>
            @endif
        @endif

    </div>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>

            {{-- MISSING ENSEMBLE ADVISORY --}}
            <div class="text-red-500 text-sm">
                @if($showEventEnsemblesMissingAdvisory)
                    <div class="w-3/4 mx-auto">
                        This event has no ensembles attached or those ensembles have no voice parts identified,
                        therefore no pitch files can be uploaded.<br/>
                        If this is incorrect, please update the record (My Events->edit button->Ensemble information).
                    </div>
                @endif
            </div>

            @if($showPitchFiles)
                <div class="flex items-center space-x-2">

                    {{-- ADD-NEW BUTTON OPENS ADD-PARTICIPANT-FORM --}}
                    @if(! $showEventEnsemblesMissingAdvisory)
                        <button type="button" wire:click="$set('showAddForm', true)"
                                class="bg-green-500 text-white text-3xl px-2 rounded-lg" title="Add New" tabindex="-1">
                            +
                        </button>
                        <x-buttons.export/>
                    @endif
                </div>
            @else
                <div class="w-1/2 mr-20 justify-end">
                    Your 'Version Dashboard->Version Profile' page has been configured for NO pitch file availability to
                    either
                    students or teachers. Please change those settings if you want pitch files to be
                    available to either population (or both) from TheDirectorsRooms.com and StudentFolder.info.
                </div>
            @endif
        </div>

        {{-- ADD ROLE FORM --}}
        <div>
            @if($showAddForm)

                <div class="bg-gray-100 p-2 mb-4">
                    <h3 class="font-semibold">Add A New Pitch File</h3>
                    <div
                        class="flex flex-col lg:flex-row space-y-2 lg:space-y-0 lg:space-x-2 items-start"
                    >
                        {{-- SELECT VOICE PART --}}
                        <x-forms.elements.livewire.selectNarrow
                            autofocus='true'
                            hint='ALL = Will be included in ALL voice parts.'
                            label="voice part"
                            name="form.voicePartId"
                            :options="$voiceParts"
                            required='true'
                        />

                        {{-- SELECT FILE TYPE --}}
                        <x-forms.elements.livewire.selectNarrow
                            label="file type"
                            name="form.fileType"
                            :options="$fileTypes"
                            :required='true'
                        />

                        {{-- DESCRIPTION --}}
                        <x-forms.elements.livewire.inputTextWide
                            label="description"
                            name="form.description"
                            required="true"
                        />

                        {{-- ORDER BY --}}
                        <x-forms.elements.livewire.selectNarrow
                            label="order"
                            name="form.orderBy"
                            :options="$options1Thru50"
                            :required="true"
                        />

                        <div class="flex flex-col">
                            <x-forms.elements.livewire.audioFileUpload
                                label="pitch file"
                                name="pitchFile"
                                hint="mp3, ogg, wav, or pdf ONLY"
                                required="true"
                            />
                            @error('form.url')
                            <x-input-error messages="{{ $message }}" aria-live="polite"/>
                            @enderror</div>

                    </div>
                    <div class="text-start text-sm italic my-1">
                        ...or select a file from previous versions...
                    </div>
                    <x-forms.elements.livewire.selectNarrow
                        label="voice part"
                        name="form.previousVoicePartId"
                        :option0="true"
                        :options="$previousVersionPitchFiles"
                        required='true'
                    />


                    {{-- SUBMIT --}}
                    <div class="flex -mt-8 ">{{-- offset for fauxSubmit label --}}
                        <x-buttons.fauxSubmit value="Add" wireClick="addPitchFile"/>

                    </div>

                </div>
            @endif
        </div>

        {{-- EDIT ROLE FORM --}}
        <div>
            @if($showEditForm)
                <div class="bg-gray-100 p-2">
                    <div class="bg-gray-100 p-2 mb-4">
                        <h3 class="font-semibold">Edit A Pitch File</h3>
                        <div
                            class="flex flex-col lg:flex-row space-y-2 lg:space-y-0 lg:space-x-2 items-start"
                        >
                            {{-- SELECT VOICE PART --}}
                            <x-forms.elements.livewire.selectNarrow
                                autofocus='true'
                                hint='ALL = Will be included in ALL voice parts.'
                                label="voice part"
                                name="form.voicePartId"
                                :options="$voiceParts"
                                required='true'
                            />

                            {{-- SELECT FILE TYPE --}}
                            <x-forms.elements.livewire.selectNarrow
                                label="file type"
                                name="form.fileType"
                                :options="$fileTypes"
                                :required='true'
                            />

                            {{-- DESCRIPTION --}}
                            <x-forms.elements.livewire.inputTextWide
                                label="description"
                                name="form.description"
                                required="true"
                            />

                            {{-- ORDER BY --}}
                            <x-forms.elements.livewire.selectNarrow
                                label="order"
                                name="form.orderBy"
                                :options="$options1Thru50"
                                :required="true"
                            />

                        </div>

                        {{-- SUBMIT --}}
                        <div class="flex -mt-8 ">{{-- offset for fauxSubmit label --}}
                            <x-buttons.fauxSubmit value="Update" wireClick="pitchFileUpdate"/>
                        </div>

                    </div>
                </div>
            @endif
        </div>

        {{-- FILTERS and TABLE --}}
        <div class="flex flex-row ">

            @if($showPitchFiles)
                {{-- FILTERS --}}
                @if($hasFilters && count($filterMethods))
                    <div class="flex justify-center">
                        <x-sidebars.filters :filters="$filters" :methods="$filterMethods"/>
                    </div>
                @endif

                {{-- TABLE WITH LINKS --}}
                <div class="flex flex-col space-y-2 mb-2 w-full">

                    <x-links.linkTop :recordsPerPage="$recordsPerPage" :rows="$rows"/>

                    {{-- TABLE --}}
                    <x-tables.pitchFilesTable
                        :columnHeaders="$columnHeaders"
                        :header="$dto['header']"
                        :recordsPerPage="$recordsPerPage"
                        :rows="$rows"
                        :sortAsc="$sortAsc"
                        :sortColLabel="$sortColLabel"
                    />

                    {{-- LINKS:BOTTOM --}}
                    <x-links.linkBottom :rows="$rows"/>

                </div>
            @endif

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>

</div>



