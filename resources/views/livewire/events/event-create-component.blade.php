<div>
    <div class="px-4">
        <h2>Add {{ ucwords($header) }}</h2>

        <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

        <div class="flex flex-col md:flex-row">

            <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg space-y-4"
                  enctype="multipart/form-data"
            >

                <x-forms.styles.genericStyle/>

                {{-- SYS ID --}}
                <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" wireModel="form.sysId"/>

                {{-- NAME --}}
                <x-forms.elements.livewire.inputTextWide
                    blur=""
                    label="name"
                    name="form.name"
                    placeholder=""
                    required
                />

                {{-- SHORT NAME --}}
                <x-forms.elements.livewire.inputTextNarrow
                    blur=""
                    label="short name"
                    name="form.shortName"
                    placeholder=""
                    required
                />

                {{-- ORGANIZATION NAME --}}
                <x-forms.elements.livewire.inputTextWide
                    blur=""
                    label="organization name"
                    name="form.orgName"
                    placeholder=""
                    required
                />

                {{-- EVENT LOGO UPLOAD --}}
                <x-forms.elements.livewire.imageFileUpload
                    blur=""
                    label="logo file"
                    name="logo"
                    placeholder=""
                />

                {{-- SHOW LOGO --}}
                @if($form->logoFile)
                    <img src="{{ $logo->temporaryUrl() }}" width="50">
                @endif

                {{-- GRADES --}}
                <x-forms.elements.livewire.inputTextNarrow
                    blur=""
                    label="eligible grades"
                    name="form.grades"
                    hint="Enter comma-separated values (ex. 9,10,11)"
                    required
                />

                {{-- STATUS --}}
                <x-forms.elements.livewire.selectNarrow
                    label="status"
                    name="form.statusId"
                    :options="$statuses"
                    required="required"
                />

                {{-- MAX REGISTRANTS --}}
                <x-forms.elements.livewire.selectNarrow
                    label="maximum number of registrants"
                    name="form.maxRegistrants"
                    :options="$maxRegistrantOptions"
                    required="required"
                    hint="Use 0 to represent no maximum."
                />

                {{-- MAX UPPER VOICE REGISTRANTS --}}
                <x-forms.elements.livewire.selectNarrow
                    label="maximum number of upper voice registrants"
                    name="form.maxUpperVoices"
                    :options="$maxRegistrantOptions" {{-- use same option values as $maxRegistrants above --}}
                    required="required"
                    hint="Use 0 to represent no maximum of Soprano and Alto registrants."
                />

                {{-- ENSEMBLE COUNT --}}
                <x-forms.elements.livewire.selectNarrow
                    label="ensemble count"
                    name="form.ensembleCountId"
                    :options="$ensembleCountOptions"
                    required="required"
                    hint="Select the number of ensembles performing in the event."
                />

                {{-- HEIGHT REQUIREMENT --}}
                <x-forms.elements.livewire.inputCheckbox
                    blur="false"
                    label="height is required"
                    name="form.requiredHeight"
                    livewire="true"
                />

                {{-- SHIRT SIZE REQUIREMENT --}}
                <x-forms.elements.livewire.inputCheckbox
                    blur="false"
                    label="shirt size is required"
                    name="form.requiredShirtSize"
                    livewire="true"
                />

                <div class="flex flex-row space-x-2">
                    {{-- SUBMIT AND RETURN TO TABLE VIEW--}}
                    <x-buttons.submit/>
                </div>

                {{-- SUCCESS INDICATOR --}}
                <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                                     message="{{  $successMessage }}"/>
            </form>
        </div>
    </div>
</div>
