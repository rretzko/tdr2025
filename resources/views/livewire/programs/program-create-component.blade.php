<div class="px-4">
    <h2>Add New Program</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

        <div class="space-y-4">
            <x-forms.styles.genericStyle/>

            <div class="flex flex-col sm:flex-row ">

                {{-- ENSEMBLE DEFINITION FIELDS --}}
                <div id="programForm">

                    {{-- SYS ID --}}
                    <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" :wireModel="$form->sysId"/>

                    {{-- SCHOOL --}}
                    @if($school->id)
                        <x-forms.elements.livewire.labeledInfoOnly
                            label="School"
                            data="{{  $schoolName }}"
                            wireModel="schoolName"
                        />
                    @else
                        <x-forms.elements.livewire.selectWide
                            label="school"
                            name="form.schoolId"
                            :options="$schools"
                            required="required"
                        />
                    @endif

                    {{-- SCHOOL YEAR --}}
                    <x-forms.elements.livewire.selectWide
                        label="school year"
                        name="form.schoolYear"
                        :options="$schoolYears"
                        required="required"
                    />

                    {{-- PROGRAM TITLE --}}
                    <x-forms.elements.livewire.inputTextWide
                        label="Program Title"
                        name="form.programTitle"
                        placeholder="Concert Program Title"
                        required
                    />

                    {{-- PROGRAM SUBTITLE --}}
                    <x-forms.elements.livewire.inputTextWide
                        label="Program Subtitle"
                        name="form.programSubtitle"
                        placeholder="Optional Subtitle"
                    />

                    {{-- PERFORMANCE DATE --}}
                    <x-forms.elements.livewire.inputDate
                        label="performance date"
                        name="form.performanceDate"
                        required="true"
                        type="date"
                    />

                    {{-- TAGS --}}
                    <x-forms.elements.livewire.inputTextArea
                        label="tags"
                        name="form.tags"
                        hint="Enter comma-separated values that you might use to search for this program"
                    />


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
        </div>
    </form>
</div>
