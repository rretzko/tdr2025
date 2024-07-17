<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">

        {{-- TABS --}}
        <x-tabs.studentEditTabs :tabs="$tabs" :selected-tab="$selectedTab"/>

        {{-- FORM --}}
        <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

            <div class="space-y-4">
                <x-forms.styles.genericStyle/>

                {{-- SYS ID --}}
                <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" wireModel="form.sysId"/>

                <fieldset id="adjudication">
                    @if($selectedTab === 'adjudication')

                        {{-- FILE COUNT and FILE TYPES --}}
                        @if($version->upload_type !== 'none')

                            <x-forms.elements.livewire.selectNarrow
                                autofocus='true'
                                label="How many {{ $version->upload_type }} files will be uploaded by each student?"
                                name="form.fileUploadCount"
                                :options="$count1thru5Options" {{-- 1-thru-5 --}}
                                required="true"
                            />

                            <x-forms.elements.livewire.inputTextWide
                                label="What file types will be uploaded?"
                                hint="Separate each file type with a comma: ex. Scales,Solo,Quartet."
                                name="form.fileTypes"
                                required="true"
                                placeholder="Scales,Amazing Grade,The Silver Swan"
                            />
                        @endif {{-- @if($selectedTab = 'adjudication') --}}

                        {{-- JUDGE COUNT --}}
                        <div class="mt-2">
                            <x-forms.elements.livewire.selectNarrow
                                label="How many judges per room?"
                                name="form.judgeCount"
                                :options="$count1thru5Options" {{-- 1-thru-5 --}}
                                required="true"
                            />
                        </div>

                        {{-- ROOM MONITOR --}}
                        <x-forms.elements.livewire.inputCheckbox
                            label="We plan to use an additional room monitor."
                            name="form.roomMonitor"
                            value="1"
                        />

                        {{-- MISSING JUDGE WORKFLOW --}}
                        <x-forms.elements.livewire.inputCheckbox
                            label="Scores should be averaged if a judge is missing."
                            name="form.averagedScores"
                            value="1"
                        />

                        {{-- SCORING ORDER --}}
                        <div class="flex flex-col mt-4 space-x-2">
                            <label>In which order should total scores be ranked?</label>
                            <div class="flex flex-col">
                                <div class="flex flex-row space-x-2 items-center">
                                    <input type="radio"
                                           wire:model="form.scoreAscending"
                                           value="1"
                                           aria-label="ascending scores checkbox"
                                    />
                                    <label>Ascending Order (golf, low score wins)</label>
                                </div>
                                <div class="flex flex-row space-x-2 items-center">
                                    <input type="radio"
                                           wire:model="form.scoreAscending"
                                           value="0"
                                           aria-label="descending scores checkbox"
                                    />
                                    <label>Descending Order (bowling, high score wins)</label>
                                </div>
                            </div>

                        </div> {{-- END OF SCORING ORDER --}}

                        {{-- ALTERNATING SCORES --}}
                        @if($event->ensemble_count > 1)
                            <x-forms.elements.livewire.inputCheckbox
                                label="Score assignment should alternate between event ensembles."
                                name="form.alternatingScores"
                                value="1"
                            />
                        @endif

                        @if($showSuccessIndicator)
                            <div class="text-green-600 italic text-xs">
                                {{ $successMessage }}
                            </div>
                        @endif

                        <x-buttons.fauxSubmit/>

                    @endif {{-- IF $SELECTEDTAB===adjudication --}}

                </fieldset>{{-- ADJUDICATION FIELDSET --}}

                <fieldset id="fees">

                    @if($selectedTab === 'fees')

                        @if($showSuccessIndicator)
                            <div class="text-green-600 italic text-xs">
                                {{ $successMessage }}
                            </div>
                        @endif

                        <x-buttons.fauxSubmit/>

                    @endif {{-- END OF @if($selectedTab = 'adjudication') --}}

                </fieldset> {{-- END OF FEES FIELDSET --}}

            </div>
        </form>

    </div>{{-- END OF ID=CONTAINER --}}

</div>


