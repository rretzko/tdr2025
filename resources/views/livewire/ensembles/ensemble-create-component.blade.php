<div class="px-4">
    <h2>Add {{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

        <div class="space-y-4">
            <x-forms.styles.genericStyle/>

            {{-- SYS ID --}}
            <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" :wireModel="$form->sysId"/>

            <fieldset id="user-info">


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
                        name="schoolId"
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

                {{-- SHORT NAME --}}
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

            </fieldset>
            {{--
                        <fieldset id="student-info">



                            <fieldset class="flex flex-col md:flex-row md:space-x-2">

                                {{-- GRADE/CLASS_OF --}
                                <x-forms.elements.livewire.selectNarrow
                                    label="grade"
                                    name="form.classOf"
                                    :options="$grades"
                                    required="required"
                                    :hint="$hintClassOf"
                                />

                                {{-- VOICE PART --}
                                <x-forms.elements.livewire.selectNarrow
                                    label="voice part"
                                    name="form.voicePartId"
                                    :options="$voiceParts"
                                    required="required"
                                />

                                {{-- HEIGHT --}
                                <x-forms.elements.livewire.selectNarrow
                                    label="height"
                                    name="form.heightInInches"
                                    option0
                                    :options="$heights"
                                    required="required"
                                />

                                {{-- BIRTHDAY --}
                                <x-forms.elements.livewire.inputTextNarrow
                                    label="birthday"
                                    name="form.birthday"
                                    type='date'
                                    :hint="$hintBirthday"
                                />

                                {{-- SHIRT SIZE --}
                                <x-forms.elements.livewire.selectNarrow
                                    label="shirt size"
                                    name="form.shirtSize"
                                    :options="$shirtSizes"
                                />

                            </fieldset>

                        </fieldset>

                        <fieldset id="phones">

                            {{-- PHONE.MOBILE --}
                            <x-forms.elements.livewire.inputTextWide
                                label="cell phone"
                                name="form.phoneMobile"
                            />

                            <x-forms.elements.livewire.inputTextWide
                                label="home phone"
                                name="form.phoneHome"
                            />
                        </fieldset>

                        {{-- DUPLICATE STUDENT ADVISORY --}
                        @if($form->duplicateStudentAdvisory)
                            <div class="border border-red-600 p-2 text-red-600 w-1/2 rounded-lg">
                                {!! $form->duplicateStudentAdvisory !!}
                            </div>
                        @endif
            --}}
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


