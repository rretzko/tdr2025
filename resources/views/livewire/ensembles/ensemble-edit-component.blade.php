<div class="px-4">
    <h2>Add {{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

        <div class="space-y-4">
            <x-forms.styles.genericStyle/>

            <div class="flex flex-col sm:flex-row ">

                {{-- ENSEMBLE DEFINITION FIELDS --}}
                <div id="ensembleDefinition">

                    {{-- SYS ID --}}
                    <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" wireModel="form.sysId"/>

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
                            option0
                            :options="$schools"
                            required="required"
                        />
                    @endif

                    {{-- NAME --}}
                    <x-forms.elements.livewire.inputTextWide
                        label="name"
                        name="form.name"
                        placeholder="High School Concert Choir"
                        required
                        autofocus
                    />

                    {{-- SHORT NAME --}}
                    <x-forms.elements.livewire.inputTextNarrow
                        label="short name"
                        name="form.shortName"
                        placeholder="Concert Choir"
                        required
                    />

                    {{-- ABBREVIATION --}}
                    <x-forms.elements.livewire.inputTextNarrow
                        label="abbreviation"
                        name="form.abbr"
                        placeholder="CC"
                        required
                    />

                    {{-- DESCRIPTION --}}
                    <x-forms.elements.livewire.inputTextArea
                        label="description"
                        name="form.description"
                        required
                    />

                    {{-- ACTIVE --}}
                    <x-forms.elements.livewire.inputCheckbox
                        label="active"
                        name="form.active"
                    />

                </div>

                {{-- ENSEMBLE ASSET CHECKBOXES --}}
                <div class="mt-4 sm:mt-0 sm:ml-4 sm:border border-white border-l-gray-200 sm:px-4">
                    <h3 class="underline bold">Assets assigned to members</h3>
                    @foreach($assets AS $asset)

                        <div wire:key="asset-{{ $asset->id }}" class="-mb-4">
                            <x-forms.elements.livewire.inputCheckbox
                                label="{{ $asset->name }}"
                                name="form.ensembleAssets"
                                value="{{ $asset->id }}"
                            />
                        </div>
                    @endforeach

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
