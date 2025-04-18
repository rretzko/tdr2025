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
                                :options="$count1thru5Options" 1-thru-5
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
                                :options="$count1thru5Options" 1-thru-5
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
                                           wire:model="form.scoresAscending"
                                           value="1"
                                           aria-label="ascending scores checkbox"
                                    />
                                    <label>Ascending Order (golf, low score wins)</label>
                                </div>
                                <div class="flex flex-row space-x-2 items-center">
                                    <input type="radio"
                                           wire:model="form.scoresAscending"
                                           value="0"
                                           aria-label="descending scores checkbox"
                                    />
                                    <label>Descending Order (bowling, high score wins)</label>
                                </div>
                            </div>

                        </div>  {{-- END OF SCORING ORDER --}}

                        {{-- ALTERNATING SCORES --}}
                        @if($event->ensemble_count > 1)
                            <x-forms.elements.livewire.inputCheckbox
                                blur="false"
                                label="Score assignment should alternate between event ensembles."
                                live="true"
                                name="form.alternatingScores"
                                value="1"
                            />

                            @if($form->alternatingScores)
                                <div class="ml-8 mt-1">
                                    <label>Choose which ensemble is assigned first:</label>
                                    <div class="flex flex-row ml-2">
                                        <label class="flex flex-row items-center space-x-2">
                                            <input type="radio" wire:model="form.firstEnsembleId" value="6"/>
                                            <div>Mixed Chorus</div>
                                        </label>
                                    </div>
                                    <div class="flex flex-row ml-2">
                                        <label class="flex flex-row items-center space-x-2">
                                            <input type="radio" wire:model="form.firstEnsembleId" value="7"/>
                                            <div>Treble Chorus</div>
                                        </label>
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- SHOW ALL SCORES PDF OPTION --}}
                        <x-forms.elements.livewire.inputCheckbox
                            label="Participating teachers should have access to all audition scores without identifying participants other than their own school."
                            name="form.showAllScores"
                            value="1"
                        />

                        {{-- SUCCESS INDICATOR --}}
                        @if($showSuccessIndicator)
                            <div class="text-green-600 italic text-xs">
                                {{ $successMessage }}
                            </div>
                        @endif

                        {{-- SUBMIT BUTTON --}}
                        <x-buttons.fauxSubmit/>

                    @endif  {{-- END OF IF $SELECTEDTAB===adjudication --}}

                </fieldset> {{-- END OF ADJUDICATION FIELDSET --}}

                {{-- REGISTRANTS --}}
                <fieldset id="registrants" class="space-y-2">

                    @if($selectedTab === 'registrants')

                        {{-- EAPPLICATION --}}
                        <x-forms.elements.livewire.inputCheckbox
                            label="This event version will use an eApplication."
                            name="form.eapplication"
                            value="1"
                        />

                        {{-- AUDITION COUNT --}}
                        <x-forms.elements.livewire.selectNarrow
                            autofocus='true'
                            label="How many voice parts is each registrant allowed to audition?"
                            name="form.auditionCount"
                            :options="$count1thru5Options" 1-thru-5
                            required="true"
                        />

                        @if($showSuccessIndicator)
                            <div class="text-green-600 italic text-xs">
                                {{ $successMessage }}
                            </div>
                        @endif

                        <x-buttons.fauxSubmit/>

                    @endif   {{-- END OF @if($selectedTab = 'adjudication') --}}

                </fieldset> {{-- END OF REGISTRANTS FIELDSET --}}

                {{-- MEMBERSHIP --}}
                <fieldset id="membership" class="space-y-2">

                    @if($selectedTab === 'membership')

                        {{-- MEMBERSHIP CARD --}}
                        <x-forms.elements.livewire.inputCheckbox
                            label="This event version requires a copy of a membership card."
                            name="form.membershipCard"
                            value="1"
                            live="true"
                        />

                        {{-- VALID THRU --}}
                        @if($form->membershipCard)
                            <x-forms.elements.livewire.inputDate
                                label="Membership must be valid through:"
                                name="form.validThru"
                                type="date"
                            />
                        @endif

                        @if($showSuccessIndicator)
                            <div class="text-green-600 italic text-xs">
                                {{ $successMessage }}
                            </div>
                        @endif

                        <x-buttons.fauxSubmit/>

                    @endif   {{-- END OF @if($selectedTab = 'adjudication') --}}

                </fieldset> {{-- END OF MEMBERSHIP FIELDSET --}}

                {{-- ADVISORY --}}
                <fieldset id="advisory" class="space-y-2">

                    @if($selectedTab === 'advisory')

                        @forelse($form->advisories AS $advisory)
                            <div class="border border-white border-b-gray-200">
                                {!! $advisory !!}
                            </div>
                        @empty
                            <div>No advisories found.</div>
                        @endforelse

                    @endif

                </fieldset> {{-- END OF ADVISORY --}}


            </div>
        </form>

    </div>{{-- END OF ID=CONTAINER --}}

</div>
