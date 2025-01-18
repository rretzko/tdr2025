<div class="px-4">
    <h2>Add {{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

        <div class="space-y-4">
            <x-forms.styles.genericStyle/>

            <div class="flex flex-col ">

                {{-- SCHOOL ENSEMBLE MEMBER FIELDS --}}
                <div id="schoolEnsembleMemberDefinition">

                    <div>
                        {{-- SYS ID --}}
                        <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" wireModel="form.sysId"/>

                        {{-- SCHOOLS --}}
                        @if(count($schools) === 1)
                            <x-forms.elements.livewire.labeledInfoOnly
                                label="school"
                                wireModel="form.schoolName"
                            />
                        @else
                            <x-forms.elements.livewire.selectWide
                                autofocus
                                label="school"
                                name="form.schoolId"
                                option0
                                :options="$schools"
                                required="required"
                            />
                        @endif

                        {{-- ENSEMBLES --}}
                        @if(count($ensembles) === 1)
                            <x-forms.elements.livewire.labeledInfoOnly
                                label="ensemble"
                                wireModel="form.ensembleName"
                            />
                        @else
                            <x-forms.elements.livewire.selectWide
                                label="ensemble"
                                name="form.ensembleId"
                                option0
                                :options="$ensembles"
                                required="required"
                            />
                        @endif

                        {{-- SCHOOL YEAR --}}
                        <x-forms.elements.livewire.inputTextNarrow
                            label="School Year"
                            name="form.schoolYear"
                            required
                            hint="Enter the school year for this member (ex. 2025 = school year: 2024-25)."
                        />

                        {{-- CHECKBOX AND INDIVIDUAL SELECTIONS --}}
                        <div class="flex flex-col mb-4 sm:flex-row sm:space-x-4 ">

                            {{-- SINGLE SELECTION --}}
                            <div>
                                {{-- NAME --}}
                                <x-forms.elements.livewire.inputTextWide
                                    blur=""
                                    label="member name"
                                    name="form.name"
                                    placeholder="Enter first or last name"
                                    required
                                    :results="$resultsName"
                                />

                                {{-- VOICE PARTS --}}
                                <x-forms.elements.livewire.selectWide
                                    label="voice part"
                                    name="form.voicePartId"
                                    :options="$voiceParts"
                                    required="required"
                                />

                                {{-- OFFICES --}}
                                <x-forms.elements.livewire.selectWide
                                    label="office"
                                    name="form.office"
                                    :options="$offices"
                                    required="required"
                                />

                                {{-- STATUS --}}
                                <x-forms.elements.livewire.selectWide
                                    label="status"
                                    name="form.status"
                                    :options="$statuses"
                                    required="required"
                                />

                            </div>

                        </div>

                        <div class="flex flex-row space-x-2">
                            {{-- SUBMIT AND RETURN TO TABLE VIEW--}}
                            <x-buttons.submit/>

                            {{-- SUBMIT AND STAY --}}
                            <x-buttons.submitAndStay/>

                        </div>
                    </div>{{-- END OF SINGLE SELECTION --}}

                </div>{{-- END OF CHECKBOX AND SINGLE SELECTION --}}

                {{-- SUCCESS INDICATOR --}}
                <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                                     message="{{  $successMessage }}"/>
            </div>
        </div>
    </form>
</div>
