<div class=" w-1/2 p-2 bg-gray-100 rounded-lg">

    <div class="flex flex-row justify-between mr-4 mb-2">
        <header class="">
            New Student Member Form
        </header>
        <button wire:click="hideEnsembleStudentRoster(true)" class="text-red-500">
            Hide...
        </button>
    </div>

    <div>

        <div>
            {{ $schoolName }}
        </div>

        <div>
            {{ $schoolYear }} {{ $ensembleName }}
        </div>

        {{-- STUDENT NAME --}}
        <div class="mb-2">
            <label>Student Name</label>
            <x-forms.elements.livewire.inputTextCompressedRow
                label="first name"
                name="form.firstName"
                :required=true
                :autofocus=true
            />
            <x-forms.elements.livewire.inputTextCompressedRow
                label="middle name"
                name="form.middleName"
            />
            <x-forms.elements.livewire.inputTextCompressedRow
                label="last name"
                name="form.lastName"
                :required=true
            />
        </div>
    </div>

    {{-- EMAIL --}}
    <x-forms.elements.livewire.inputTextWide
        label="email"
        name="form.email"
        hint="A proxy email address will be created by default.  Overwrite this if the email is known."
        :required=true
    />

    {{-- GRADE or CLASSOF --}}
    <x-forms.elements.livewire.inputTextWide
        label="grade or class of"
        name="form.gradeClassOf"
        hint="ex. 9 or {{ $form->schoolYear }}, defaults to program school year"
        :required=true
    />

    {{-- VOICE PART ID --}}
    <div class="mb-2">
        <label class=" flex flex-col">
            <div>Voice Part<span class="text-red-500">*</span></div>
            <select wire:model="form.voicePartId" class="w-fit">
                @foreach($ensembleVoicings AS $id => $voicePart)
                    <option value="{{ $id }}">{{ $voicePart }}</option>
                @endforeach

            </select>
        </label>
    </div>

    {{-- OFFICE --}}
    <x-forms.elements.livewire.inputTextWide
        label="office"
        name="form.office"
    />

    <div class="flex flex-row space-x-2">
        <x-buttons.submit type="button" value="submit" :livewire=true wireClick="clickAddNewMember"/>
        <x-buttons.submitAndStay type="button" value="submit and stay" :livewire=true
                                 wireClick="clickAddNewMemberStay"/>
    </div>
</div>

</div>
