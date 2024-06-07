<div class="px-4">
    <h2>{{ ucwords($header) }}: {{ $fullName }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">

        {{-- TABS --}}
        <x-tabs.studentEditTabs :tabs="$tabs" :selected-tab="$selectedTab"/>

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
