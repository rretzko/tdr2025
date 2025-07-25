<div class="px-4">
    <h2>Edit Existing Program</h2>

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
                    <x-forms.elements.livewire.selectWide
                        label="school"
                        name="form.schoolId"
                        :options="$schools"
                        required="required"
                    />

                    {{-- SCHOOL YEAR --}}
                    <x-forms.elements.livewire.selectWide
                        label="school year"
                        name="form.schoolYear"
                        :options="$schoolYears"
                        required="required"
                        autofocus="true"
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

                    {{-- PROGRAM ORGANIZATION --}}
                    <div class="my-2">
                        <header>This program is organized by:</header>
                        <div class="ml-4 text-sm">
                            <div class="flex items-center space-x-1">
                                <input type="radio" wire:model="form.organizedBy" value="ensemble"/>
                                <label>Ensemble
                                    <hint>(ex. spring concert)</hint>
                                </label>
                            </div>

                            <div class="flex items-center space-x-1">
                                <input type="radio" wire:model="form.organizedBy" value="act"/>
                                <label>Act
                                    <hint>(ex. cabaret, senior solo recital)</hint>
                                </label>
                            </div>
                        </div>

                    </div>

                    {{-- TAGS --}}
                    <x-forms.elements.livewire.inputTextArea
                        label="tags"
                        name="form.tags"
                        hint="Enter comma-separated values that you might use to search for this program"
                    />


                </div>

            </div>

            @if($successMessage)
                <div class="px-2 text-sm italic bg-green-100 text-green-800 w-fit rounded-lg">
                    {{ $successMessage }}
                </div>
            @endif

            @if($programExistsMessage)
                <div class="px-2 text-sm italic bg-red-100 text-red-800 w-fit rounded-lg">
                    {!! $programExistsMessage !!}
                </div>
            @endif

            {{-- SUBMIT --}}
            <div class="flex flex-row mt-2 space-x-4 max-w-xs">
                <button type="submit"
                        class="bg-gray-800 text-white px-2 rounded-full disabled:cursor-not-allowed disabled:opacity-50"
                >
                    Update
                </button>

                <button type="button"
                        class="bg-gray-800 text-white px-2 rounded-full disabled:cursor-not-allowed disabled:opacity-50"
                        wire:click="saveAndStay()"
                >
                    Update and Stay on Page
                </button>
            </div>


            {{-- SUCCESS INDICATOR --}}
            <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                                 message="{{  $successMessage }}"/>
        </div>
    </form>
</div>

