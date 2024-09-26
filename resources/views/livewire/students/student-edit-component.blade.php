<div class="px-4">
    <h2>{{ ucwords($header) }}: {{ $fullName }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">

        {{-- TABS --}}
        {{-- Display tabs for ACTIVE students only --}}
        @if($form->active)
            <x-tabs.studentEditTabs :tabs="$tabs" :selected-tab="$selectedTab"/>
        @endif

        {{-- FORM --}}
        <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

            <div class="space-y-4">
                <x-forms.styles.genericStyle/>

                {{-- SYS ID --}}
                <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" data="$sysId" wireModel="sysId"/>

                <fieldset id="user-info">

                    {{-- NAMES --}}
                    <fieldset class="flex flex-col md:flex-row space-x-2" id="name-parts">

                        {{-- NAME.FIRST --}}
                        <x-forms.elements.livewire.inputTextNarrow
                            label="first"
                            name="form.first"
                            required
                            autofocus
                        />

                        {{-- NAME.MIDDLE --}}
                        <x-forms.elements.livewire.inputTextNarrow
                            label="middle"
                            name="form.middle"
                        />

                        {{-- NAME.LAST --}}
                        <x-forms.elements.livewire.inputTextNarrow
                            label="last"
                            name="form.last"
                            required
                        />

                        {{-- NAME.SUFFIX --}}
                        <x-forms.elements.livewire.inputTextNarrow
                            label="suffix"
                            name="form.suffix"
                            hint="Jr., III, etc."
                        />

                    </fieldset>

                    <div class="mb-2 text-green-600 italic text-xs">
                        @if($successMessage)
                            {{ $successMessage }}
                        @endif
                    </div>

                    {{-- PREFERRED PRONOUN --}}
                    <x-forms.elements.livewire.selectWide
                        label="preferred pronoun"
                        name="form.pronounId"
                        :options="$pronouns"
                        required="required"
                    />

                    <div class="mb-2 text-green-600 italic text-xs">
                        @if($successMessagePronoun)
                            {{ $successMessagePronoun }}
                        @endif
                    </div>

                </fieldset>

                <fieldset id="student-info">

                    {{-- SCHOOL --}}
                    <x-forms.elements.livewire.selectWide
                        label="school"
                        name="form.schoolId"
                        option0
                        :options="$schools"
                        required="required"
                    />

                    <div class="mb-2 text-green-600 italic text-xs">
                        @if($successMessageSchoolId)
                            {{ $successMessageSchoolId }}
                        @endif
                    </div>

                    <div class="mb-2">
                        <x-forms.elements.livewire.inputCheckbox
                            hint='' {{-- For students, only one school can be active at a time.' --}}
                        label='active?'
                            live="true"
                            name="form.active"
                            type='bool'
                            value='1'
                        />
                        @if(! $form->active)
                            <div class="block border border-red-800 rounded-lg p-2 mt-2 text-red-600 bg-red-50 w-2/3">
                                You have marked this student as <b>inactive</b>.<br/>
                                When you leave this page, the "Edit" button will be removed from the Students table for
                                this student.
                                You will lose the ability to further edit this student's record.
                            </div>
                        @endif
                    </div>

                    <div class="mb-2 text-green-600 italic text-xs">
                        @if($successMessageActive)
                            {{ $successMessageActive }}
                        @endif

                    </div>

                    <fieldset class="flex flex-col md:flex-row md:space-x-2">

                        {{-- GRADE/CLASS_OF --}}
                        <x-forms.elements.livewire.selectNarrow
                            label="grade"
                            name="form.classOf"
                            :options="$grades"
                            required="required"
                            :hint="$hintClassOf"
                        />

                        {{-- VOICE PART --}}
                        <x-forms.elements.livewire.selectNarrow
                            label="voice part"
                            name="form.voicePartId"
                            :options="$voiceParts"
                            required="required"
                        />

                        {{-- HEIGHT --}}
                        <x-forms.elements.livewire.selectNarrow
                            label="height"
                            name="form.heightInInches"
                            option0
                            :options="$heights"
                            required="required"
                        />

                        {{-- BIRTHDAY --}}
                        <x-forms.elements.livewire.inputTextNarrow
                            label="birthday"
                            name="form.birthday"
                            type='date'
                            :hint="$hintBirthday"
                        />

                        {{-- SHIRT SIZE --}}
                        <x-forms.elements.livewire.selectNarrow
                            label="shirt size"
                            name="form.shirtSize"
                            :options="$shirtSizes"
                        />

                    </fieldset>

                    <div class="mb-2 text-green-600 italic text-xs">
                        @if($successMessageSchool)
                            {{ $successMessageSchool }}
                        @endif
                    </div>

                </fieldset>

            </div>
        </form>

    </div>{{-- END OF ID=CONTAINER --}}

</div>
