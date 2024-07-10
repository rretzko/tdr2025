<div>
    <div class="px-4">
        <h2>Add {{ ucwords($header) }}</h2>

        <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

        <div class="flex flex-col md:flex-row">
            <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg space-y-4">

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
                <x-forms.elements.livewire.inputTextNarrow
                    blur=""
                    label="logo file"
                    name="form.logo"
                    placeholder=""
                    required
                />

                <x-forms.elements.livewire.inputTextNarrow
                    blur=""
                    label="eligible grades"
                    name="form.grades"
                    hint="Enter comma-separated values (ex. 9,10,11)"
                    required
                />

                <x-forms.elements.livewire.selectNarrow
                    label="status"
                    name="form.statusId"
                    :options="$statuses"
                    required="required"
                />

                <x-forms.elements.livewire.selectNarrow
                    label="maximum number of registrants"
                    name="form.maxRegistrants"
                    :options="$maxRegistrantOptions"
                    required="required"
                    hint="Use 0 to represent no maximum."
                />

                <x-forms.elements.livewire.selectNarrow
                    label="ensemble count"
                    name="form.ensembleCountId"
                    :options="$ensembleCountOptions"
                    required="required"
                    hint="Select the number of ensembles performing in the event."
                />

                <x-forms.elements.livewire.inputCheckbox
                    label="height is required"
                    name="form.requiredHeight"
                    livewire="true"
                />

                <x-forms.elements.livewire.inputCheckbox
                    label="shirt size is required"
                    name="form.requiredShirtSize"
                />

                <div class="flex flex-row space-x-2">
                    {{-- SUBMIT AND RETURN TO TABLE VIEW--}}
                    <x-buttons.submit/>
                </div>

                {{-- SUCCESS INDICATOR --}}
                <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                                     message="{{  $successMessage }}"/>
            </form>

            {{-- ENSEMBLE INFORMATION --}}
            <div class="flex-flex-row ml-0 md:ml-2 space-y-1"
            @for($i=0; $i<=$form->ensembleCountId; $i++)

                <form class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg space-y-4">

                    <h3 class="font-semibold">
                        Ensemble #{{ $i+1 }} Information
                    </h3>

                    {{-- NAME --}}
                    <x-forms.elements.livewire.inputTextWide
                        blur="true"
                        label="name"
                        name="form.ensembles.{{$i}}.name"
                        placeholder=""
                        required
                    />

                    {{-- SHORT NAME --}}
                    <x-forms.elements.livewire.inputTextNarrow
                        blur="true"
                        label="short name"
                        name="form.ensembles.{{$i}}.shortName"
                        placeholder=""
                        required
                    />
                </form>

            @endfor
        </div>
        <div>@json($form->ensembles)</div>

    </div>
</div>

<h3>Needed:</h3>
<ol class="ml-2">
    <li>Ensemble Info
        <ol class="ml-8">
            <li>Name</li>
            <li>Short Name</li>
            <li>Grades</li>
            <li>Voice Parts</li>
        </ol>
    </li>

</ol>
</div>
