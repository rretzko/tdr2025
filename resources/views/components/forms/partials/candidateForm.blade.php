@props([
    'auditionFiles' => [],
    'ensembleVoiceParts',
    'eventGrades',
    'heights',
    'form',
    'shirtSizes',
    'statuses',
])
<form wire:submit="save" class="space-y-2">
    {{-- NAME --}}
    <fieldset class="flex flex-row space-x-1">
        <x-forms.elements.livewire.inputTextCompressed
            label="first name"
            name="form.firstName"
            required="true"
        />
        <x-forms.elements.livewire.inputTextCompressed
            label="middle"
            name="form.middleName"
        />
        <x-forms.elements.livewire.inputTextCompressed
            label="last"
            name="form.lastName"
            required="true"
        />
        <x-forms.elements.livewire.inputTextCompressed
            label="suffix"
            name="form.suffixName"
        />
    </fieldset>

    {{-- STATUS, GRADE, VOICEPART --}}
    <fieldset class="flex flex-row space-x-1">
        <x-forms.elements.livewire.selectCompressed
            label="status"
            name="form.status"
            :options="$statuses"
            required="true"
        />
        <x-forms.elements.livewire.selectCompressed
            label="grades"
            name="form.grade"
            :options="$eventGrades"
            required="true"
        />

        <x-forms.elements.livewire.selectCompressed
            label="auditioning voice part"
            name="form.voicePartId"
            :options="$ensembleVoiceParts"
            required="true"
        />
    </fieldset>

    {{-- EMAIL, MOBILE PHONE, HOME PHONE --}}
    <fieldset class="flex flex-row space-x-1">
        <x-forms.elements.livewire.inputTextCompressed
            label="email"
            name="form.email"
        />
        <x-forms.elements.livewire.inputTextCompressed
            label="cell phone"
            name="form.phoneMobile"
        />
        <x-forms.elements.livewire.inputTextCompressed
            label="homePhone"
            name="form.phoneHome"
        />
    </fieldset>

    {{-- HEIGHT, SHIRT SIZE, PROGRAM NAME --}}
    <fieldset class="flex flex-row space-x-1 pb-2 border border-transparent border-b-gray-200">
        <x-forms.elements.livewire.selectCompressed
            label="height"
            name="form.height"
            :options="$heights"
        />
        <x-forms.elements.livewire.selectCompressed
            label="shirt size"
            name="form.shirtSize"
            :options="$shirtSizes"
        />
        <x-forms.elements.livewire.inputTextCompressed
            label="program name"
            name="form.programName"
        />
    </fieldset>

    {{-- EMERGENCY CONTACT(S) --}}
    <fieldset class="flex flex-col space-y-1 text-sm pb-2 border border-transparent border-b-gray-200">
        <h3 class="text-left font-semibold">Emergency Contacts</h3>
        <hint class="text-xs text-left italic">
            Emergency Contact information can be added/edited from the Students->edit->emergencyContact page.
        </hint>
        @forelse($form->emergencyContacts AS $emergencyContact)
            <div class="flex flex-row space-x-1">
                <div class="w-1/6">
                    {{ $emergencyContact['emergencyContactName'] }}
                </div>
                <div class="w-1/6">
                    {{ $emergencyContact['emergencyContactEmail'] }}
                </div>
                <div class="w-1/6 @if($emergencyContactBestPhone === 'mobile') font-semibold @endif">
                    {{ $emergencyContact['emergencyContactPhoneMobile'] }} (c)
                </div>
                <div class="w-1/6 @if($emergencyContactBestPhone === 'home') font-semibold @endif">
                    {{ $emergencyContact['emergencyContactPhoneMobile'] }} (h)
                </div>
                <div class="w-1/6 @if($emergencyContactBestPhone === 'work') font-semibold @endif">
                    {{ $emergencyContact['emergencyContactPhoneMobile'] }} (w)
                </div>
            </div>
        @empty
            <div class="text-left text-red-600">
                No Emergency Contacts Found!
            </div>
        @endforelse
    </fieldset>

    {{-- HOME ADDRESS --}}
    <fieldset class="flex flex-col space-y-1 text-sm pb-2 border border-transparent border-b-gray-200">
        <h3 class="text-left font-semibold">Home Address</h3>
        <hint class="text-xs text-left italic">
            Home Address information can be added/edited from the Students->edit->comms page.
        </hint>
        @if($form->homeAddress)
            <div class="text-left">
                {{ $form->homeAddress }}
            </div>
        @else
            <div class="text-left text-red-600">
                No or partial home address found.
            </div>
        @endif
    </fieldset>

    {{-- APPLICATION --}}
    <fieldset class="flex flex-col space-y-1 text-sm pb-2 border border-transparent border-b-gray-200">
        <h3 class="text-left font-semibold">Application</h3>

        <div class="flex flex-row w-full">

            <div class="w-1/2 ">
                <x-forms.elements.livewire.inputCheckbox
                    label="Click checkbox to approve signatures"
                    name="form.signatures"
                    marginTop="0"
                />
            </div>

            <a href="" class="text-left pl-2 w-1/2 border border-transparent border-l-gray-400">
                Click to display application pdf
            </a>

        </div>

    </fieldset>

    {{-- RECORDINGS --}}
    <fieldset class="flex flex-col w-full space-y-1 text-sm space-x-2">
        <h3 class="text-left font-semibold">Audition Recordings</h3>

        <div class="flex flex-col justify-start items-start space-y-2">
            @foreach($form->fileUploads AS $uploadType)
                <div class=" shadow-lg p-2" wire:key="auditionFile-{{ $uploadType }}">
                    <h4 class="font-semibold">{{ ucwords($uploadType) }} Recording</h4>
                    @if(array_key_exists($uploadType, $form->auditionFiles) && strlen($form->auditionFiles[$uploadType]))
                        <div>
                            <audio id="audioPlayer-{{ $uploadType }}" class="mx-auto" controls style="display: block">
                                <source id="audioSource-{{ $uploadType }}"
                                        src="https://auditionsuite-production.s3.amazonaws.com/{{ $form->auditionFiles[$uploadType] }}"
                                        type="audio/mpeg"
                                >
                                " Your browser does not support the audio element. "
                            </audio>
                            Play {{ $uploadType }} recording
                            {{--                            {{ $form->$auditionFiles[$uploadType] }}--}}
                        </div>
                    @else
                        <x-forms.elements.livewire.audioFileUpload
                            label=""
                            name="auditionFiles.{{  $uploadType }}"
                        />
                    @endif

                </div>
            @endforeach
        </div>

    </fieldset>
    {{--        <x-forms.elements.livewire.inputTextCompressed--}}
    {{--            label="middle"--}}
    {{--            name="form.middleName"--}}
    {{--        />--}}
    {{--        <x-forms.elements.livewire.inputTextCompressed--}}
    {{--            label="last"--}}
    {{--            name="form.lastName"--}}
    {{--        />--}}
    {{--        <x-forms.elements.livewire.inputTextCompressed--}}
    {{--            label="suffix"--}}
    {{--            name="form.suffixName"--}}
    {{--        />--}}
    {{--    </div>--}}
</form>
