<div class="px-4">
    <h2>Add {{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

        <div class="space-y-4">
            <x-forms.styles.genericStyle/>

            <div class="flex flex-col ">

                {{-- SCHOOL ENSEMBLE MEMBER FIELDS --}}
                <div id="schoolEnsembleMemberDefinition">

                    {{-- SYS ID --}}
                    <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" wireModel="form.sysId"/>

                    {{-- SCHOOLS --}}
                    <x-forms.elements.livewire.selectWide
                        autofocus
                        label="school"
                        name="form.schoolId"
                        option0
                        :options="$schools"
                        required="required"
                    />

                    {{-- ENSEMBLES --}}
                    <x-forms.elements.livewire.selectWide
                        label="ensemble"
                        name="form.ensembleId"
                        option0
                        :options="$ensembles"
                        required="required"
                    />

                    {{-- SCHOOL YEAR --}}
                    <x-forms.elements.livewire.inputTextNarrow
                        label="School Year"
                        name="form.schoolYear"
                        required
                        hint="Enter the school year for this member (ex. 2024-25 = 2025)."
                    />

                    {{-- NAME --}}
                    <x-forms.elements.livewire.inputTextWide
                        label="member name"
                        name="form.name"
                        placeholder="Enter first or last and tab out to search"
                        required
                        :results="$resultsName"
                    />

                    {{-- MEMBER GRADE/CLASSOF --}}
                    <x-forms.elements.livewire.inputTextNarrow
                        label="Member Grade/Class Of"
                        name="form.classOfGrade"
                        placeholder="2025 or 9"
                        required
                        hint="Enter 'class of' or grade for current students."
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

            {{-- SUBMIT --}}
            <x-buttons.submit/>

            {{-- SUCCESS INDICATOR --}}
            <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                                 message="{{  $successMessage }}"/>
        </div>
    </form>
</div>
