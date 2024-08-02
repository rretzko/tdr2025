@props([
    'auditionFiles' => [],
    'ensembleVoiceParts',
    'eventGrades',
    'form',
    'height',
    'heights',
    'missingApplicationRequirements',
    'pathToRegistration',
    'shirtSize',
    'shirtSizes',
    'showRegistrationPath',
    'showSuccessIndicator',
    'studentHomeAddress',
    'successMessage',
])
<form wire:submit="save" class="space-y-2">

    {{-- STATUS BAR --}}
    <div class="w-full text-center border border-gray-600 {{ $form->statusBg }} {{ $form->statusTextColor }}">
        {{ strtoupper($form->status) }}
        {{-- SUCCESS INDICATOR --}}
        @if($showSuccessIndicator)
            <div class="text-green-600 italic text-xs">
                {{ $successMessage }}
            </div>
        @endif
    </div>

    {{-- PATH TO REGISTRATION --}}
    <div class="flex flex-col justify-start text-xs">
        <button type="button"
                class="block text-left border border-gray-600 rounded-full px-2 shadow-lg bg-indigo-50"
                wire:click="$toggle('showRegistrationPath')"
        >
            @if($showRegistrationPath)
                Click to Close
            @else
                Click for Path to Registration
            @endif
        </button>
        <div id="path" class="block text-left">
            @if($showRegistrationPath)
                <h3 class="underline">Path to registration</h3>
                {!! $pathToRegistration !!}
            @endif
        </div>
    </div>

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

    {{-- GRADE, VOICEPART --}}
    <fieldset class="flex flex-row space-x-1">

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
            label="home phone"
            name="form.phoneHome"
        />
    </fieldset>

    {{-- HEIGHT, SHIRT SIZE, PROGRAM NAME --}}
    <fieldset class="flex flex-row space-x-1 pb-2 border border-transparent border-b-gray-200">
        @if($height)
            <x-forms.elements.livewire.selectCompressed
                label="height"
                name="form.height"
                :options="$heights"
            />
        @endif

        @if($shirtSize)
            <x-forms.elements.livewire.selectCompressed
                label="shirt size"
                name="form.shirtSize"
                :options="$shirtSizes"
            />
        @endif

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
                <div
                    class="w-1/6 @if($emergencyContact['emergencyContactBestPhone'] === 'mobile') font-semibold @endif">
                    {{ $emergencyContact['emergencyContactPhoneMobile'] }} (c)
                </div>
                <div class="w-1/6 @if($emergencyContact['emergencyContactBestPhone'] === 'home') font-semibold @endif">
                    {{ $emergencyContact['emergencyContactPhoneHome'] }} (h)
                </div>
                <div class="w-1/6 @if($emergencyContact['emergencyContactBestPhone'] === 'work') font-semibold @endif">
                    {{ $emergencyContact['emergencyContactPhoneWork'] }} (w)
                </div>
            </div>
        @empty
            <div class="text-left text-red-600">
                No Emergency Contacts Found!
            </div>
        @endforelse
    </fieldset>

    {{-- HOME ADDRESS --}}
    <fieldset
        class="flex flex-col space-y-1 text-sm pb-2 @if($studentHomeAddress) border border-transparent border-b-gray-200 @endif ">
        @if($studentHomeAddress)
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
        @endif
    </fieldset>

    {{-- APPLICATION --}}
    <fieldset class="flex flex-col space-y-1 text-sm pb-2 border border-transparent border-b-gray-200">
        <h3 class="text-left font-semibold">
            {{ $form->eApplication ? 'eApplication' : 'Application' }}
        </h3>

        <div class="flex flex-row w-full">

            @if($form->eApplication)
                eApplication
            @elseif(count($missingApplicationRequirements))
                <div>
                    <h3>An application cannot be created because the following requirements are missing:</h3>
                    <ul class="text-left">
                        @foreach($missingApplicationRequirements AS $missing)
                            <li>{!! $missing !!}</li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="w-1/2 ">
                    <x-forms.elements.livewire.inputCheckbox
                        blur="false"
                        label="Click checkbox to approve signatures"
                        live="true"
                        name="form.signatureTeacher"
                        marginTop="0"
                    />
                </div>
            @endif

            @if(! count($missingApplicationRequirements))
                <a href="{{ route('pdf.application',['candidate' => $form->candidate]) }}"
                   class="text-left text-blue-500 pl-2 w-1/2 border border-transparent border-l-gray-400">
                    Click to download application pdf
                </a>
            @endif

        </div>

    </fieldset>

    {{-- RECORDINGS --}}
    <fieldset class="flex flex-col w-full space-y-1 text-sm space-x-2">
        <h3 class="text-left font-semibold">Audition Recordings</h3>

        <div class="flex flex-col justify-start items-start space-y-2">
            @foreach($form->fileUploads AS $uploadType)
                <div class=" shadow-lg p-2" wire:key="auditionFile-{{ $uploadType }}">
                    <h4 class="font-semibold">{{ ucwords($uploadType) }} Recording</h4>
                    @if(array_key_exists($uploadType, $form->recordings) && count($form->recordings[$uploadType]))
                        <div>
                            <audio id="audioPlayer-{{ $uploadType }}" class="mx-auto" controls style="display: block">
                                <source id="audioSource-{{ $uploadType }}"
                                        src="https://auditionsuite-production.s3.amazonaws.com/{{ $form->recordings[$uploadType]['url'] }}"
                                        type="audio/mpeg"
                                >
                                " Your browser does not support the audio element. "
                            </audio>
                            <div class="flex flex-row w-full mt-2 space-x-4 justify-center">
                                @if(array_key_exists('approved', $form->recordings[$uploadType]) &&
                                    strlen($form->recordings[$uploadType]['approved']))
                                    <div class="text-xs text-green-600">
                                        Approved: {{ $form->recordings[$uploadType]['approved'] }}
                                    </div>
                                @else
                                    <button class="px-2 rounded-full text-sm bg-green-600 text-green-100"
                                            wire:click="recordingApprove('{{  $uploadType }}')"
                                            type="button"
                                    >
                                        Approve
                                    </button>
                                @endif
                                <button class="px-2 rounded-full text-sm bg-red-600 text-red-100"
                                        wire:click="recordingReject('{{  $uploadType }}')"
                                        wire:confirm="This will PERMANENTLY delete the uploaded {{ $uploadType }} file.\nPlease notify your student of their need to re-record this file.\nClick OK to proceed or Cancel to stop this action."
                                        type="button"
                                >
                                    Reject
                                </button>
                            </div>
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

</form>
