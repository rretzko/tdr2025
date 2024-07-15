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
                    autofocus="true"
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
                    <img src="{{ $awsBucket . $form->logoFile }}" width="50">
                @endif

                {{-- GRADES --}}
                <x-forms.elements.livewire.inputTextNarrow
                    blur="false"
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
                    label="height is required"
                    name="form.requiredHeight"
                    livewire="true"
                />

                {{-- SHIRT SIZE REQUIREMENT --}}
                <x-forms.elements.livewire.inputCheckbox
                    label="shirt size is required"
                    name="form.requiredShirtSize"
                    livewire="true"
                />

                {{-- SUCCESS INDICATOR --}}
                <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                                     message="{{  $successMessage }}"/>

                <div class="flex flex-row space-x-2">
                    {{-- SUBMIT AND RETURN TO TABLE VIEW--}}
                    <x-buttons.submit/>
                </div>

            </form>

            {{-- ENSEMBLE INFORMATION --}}
            <div class="flex-flex-row ml-0 md:ml-2 space-y-1"
            @for($i=0; $i<$form->ensembleCountId; $i++)

                <form class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg space-y-4"
                      wire:key="ensembleForm-{{$i}}">

                    <h3 class="font-semibold">
                        Ensemble #{{ ($i + 1) }} Information
                    </h3>

                    <x-forms.elements.livewire.labeledInfoOnly
                        label="Sys Id"
                        wireModel="form.ensembles.{{ $i }}.id"
                    />

                    {{-- NAME --}}
                    <x-forms.elements.livewire.inputTextWide
                        blur="false"
                        label="name"
                        name="form.ensembles.{{$i}}.name"
                        placeholder=""
                        required="true"
                    />

                    {{-- SHORT NAME --}}
                    <x-forms.elements.livewire.inputTextNarrow
                        blur="false"
                        label="short name"
                        name="form.ensembles.{{$i}}.shortName"
                        placeholder=""
                        required="true"
                    />

                    {{-- GRADES --}}
                    <div>
                        <label>Eligible Grades<span class="text-red-600">*</span></label>

                        <div class="flex flex-row space-x-2">
                            @for($j=1; $j<13; $j++)
                                <x-forms.elements.livewire.inputCheckbox
                                    blur="false"
                                    error='grades'
                                    key="grade-{{ $j }}"
                                    label="{{ $j }}"
                                    name="form.ensembles.{{ $i }}.grades"
                                    value="{{ $j }}"
                                />
                            @endfor
                        </div>

                        @error('grades')
                        <x-input-error messages="{{ $message }}" aria-live="polite"/>
                        @enderror
                    </div>

                    <div>
                        <label>Voice Parts<span class="text-red-600">*</span></label>

                        <div class="flex flex-col space-y-1">
                            @foreach($voiceParts AS $id => $descr)
                                <x-forms.elements.livewire.inputCheckbox
                                    blur="false"
                                    error='grades'
                                    key="voicePart-{{ $id }}"
                                    label="{{ $descr }}"
                                    name="form.ensembles.{{$i}}.voiceParts"
                                    value='{{$id}}'
                                />
                            @endforeach
                        </div>

                        @error('grades')
                        <x-input-error messages="{{ $message }}" aria-live="polite"/>
                        @enderror
                    </div>

                    {{-- SUCCESS INDICATOR --}}
                    <x-forms.indicators.successIndicator
                        :showSuccessIndicator="$form->ensembles[$i]['showSuccessIndicator']"
                        message="{{  $form->ensembles[$i]['successMessage'] }}"/>

                    {{-- ERROR INDICATOR --}}
                    <x-forms.indicators.errorIndicator :showErrorIndicator="$showErrorIndicator"
                                                       message="{!! $form->errors !!}"/>

                    <x-buttons.fauxSubmit
                        value='save ensemble #{{ ($i + 1) }}'
                        wireClick='saveEnsemble({{ $i }})'
                    />

                </form>

            @endfor
        </div>
    </div>
</div>

