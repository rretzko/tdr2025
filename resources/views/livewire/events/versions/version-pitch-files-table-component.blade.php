<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH and RECORDS PER PAGE --}}
    <div class="flex flex-row justify-between px-4 w-full">

        {{--  SEARCH AND RECORDS PER ROW--}}
        @if($hasSearch || (count($rows) > 15))
            @if($hasSearch)
                <x-tables.searchComponent placeholder="Search name & school"/>
            @else
                <div></div>
            @endif

            {{-- RECORDS PER PAGE --}}
            @if(count($rows) > 15)
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
            <div class="flex items-center space-x-2">

                {{-- ADD-NEW BUTTON OPENS ADD-PARTICIPANT-FORM --}}
                <button type="button" wire:click="$set('showAddRoleForm', true)"
                        class="bg-green-500 text-white text-3xl px-2 rounded-lg" title="Add New" tabindex="-1">
                    +
                </button>
                <x-buttons.export/>
            </div>
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
                            option0='true'
                            option0Label="All"
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

                        {{-- ORDER BY --}}
                        <x-forms.elements.livewire.selectNarrow
                            label="order"
                            name="form.orderBy"
                            :options="$options1Thru50"
                            :required="true"
                        />

                        {{-- DESCRIPTION --}}
                        <x-forms.elements.livewire.inputTextWide
                            label="description"
                            name="form.description"
                            required="true"
                        />

                        <x-forms.elements.livewire.audioFileUpload
                            label="pitch file"
                            name="pitchFile"
                            hint=".mp3, .ogg and .wav ONLY"
                            required="true"
                        />
                    </div>

                    {{-- SUBMIT --}}
                    <div class="flex -mt-8 ">{{-- offset for fauxSubmit label --}}
                        <x-buttons.fauxSubmit value="Add" wireClick="updateRole"/>

                    </div>

                </div>
            @endif
        </div>

        {{-- EDIT ROLE FORM --}
        <div>
            @if($showEditRoleForm)
                <div class="bg-gray-100 p-2">
                    <table class="w-11/12 mx-auto bg-white">
                        <tr>
                            <td class="border border-white px-2">
                                {{ $showEditRoleFormName }}
                            </td>
                            <td class="border border-white px-2">
                                <select wire:model="showEditRoleFormRole">
                                    @foreach($roles AS $role)
                                        <option value="{{ $role }}">
                                            {{ $role }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="border border-white px-2">
                                <button wire:click="participantRoleUpdate"
                                        class="bg-indigo-500 text-white rounded-full px-2"
                                >
                                    Update
                                </button>
                            </td>
                            <td class="border border-white px-2">
                                <button wire:click="$set('showEditParticipantForm', 0)"
                                        class="bg-gray-800 text-white rounded-full px-2"
                                >
                                    Cancel
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
            @endif
        </div>

        {{-- FILTERS and TABLE --}
        <div class="flex flex-row ">

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
            <x-tables.versionRolesTable
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

    </div>

    {{-- SUCCESS INDICATOR --}}
    <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                         message="{{  $successMessage }}"/>
</div>

</div>



