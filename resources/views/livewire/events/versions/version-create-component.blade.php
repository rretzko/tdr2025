<div class="px-4">
    <h2>Add {{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

        <div class="space-y-4">

            <x-forms.styles.genericStyle/>

            <fieldset id="bio" class=" pb-4 border border-white border-b-gray-300">

                {{-- SYS ID --}}
                <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" wireModel="createForm.sysId"/>

                {{-- NAME --}}
                <x-forms.elements.livewire.inputTextWide
                    blur=""
                    label="name"
                    name="createForm.name"
                    placeholder=""
                    required
                />

                {{-- SHORT NAME --}}
                <x-forms.elements.livewire.inputTextNarrow
                    blur=""
                    label="short name"
                    name="createForm.shortName"
                    placeholder=""
                    required
                />

                {{-- SENIOR CLASS --}}
                <x-forms.elements.livewire.selectNarrow
                    label="Senior Class"
                    name="createForm.seniorClassId"
                    :options="$seniorClasses"
                    required="required"
                />

                {{-- STATUS --}}
                <x-forms.elements.livewire.selectNarrow
                    label="status"
                    name="createForm.statusId"
                    :options="$statuses"
                    required="required"
                />
            </fieldset>

            {{-- UPLOAD FILES --}}
            <fieldset id="uploadFiles" class="space-y-0 pb-4 border border-white border-b-gray-300">
                <label>The following file type will be uploaded:</label>
                <div class="ml-4 space-y-0">
                    <div>
                        <input type="radio" wire:model="createForm.uploadType" value="audio"/>
                        <label>Audio (mp3, m4a, wav) </label>
                    </div>
                    <div>
                        <input type="radio" wire:model="createForm.uploadType" value="video"/>
                        <label>Video (mp4, mov)</label>
                    </div>
                    <div>
                        <input type="radio" wire:model="createForm.uploadType" value="none"/>
                        <label>None</label>
                    </div>
                </div>
            </fieldset>

            {{-- FEES --}}
            <fieldset id="fees" class="space-y-0 pb-4 border border-white border-b-gray-300">
                <label>The following fees will be collected:</label>
                <div class="ml-4 space-y-2">
                    <x-forms.elements.livewire.inputTextNarrow
                        blur=""
                        label="Registration Fee"
                        name="createForm.feeRegistration"
                    />
                    <x-forms.elements.livewire.inputTextNarrow
                        blur=""
                        label="On-Site Registration Fee"
                        name="createForm.feeOnSiteRegistration"
                    />
                    <x-forms.elements.livewire.inputTextNarrow
                        blur=""
                        label="Participation Fee"
                        name="createForm.feeParticipation"
                        placeholder=""
                    />
                </div>
            </fieldset>

            {{-- PayPal --}}
            <fieldset id="payPal" class="space-y-0 pb-4 border border-white border-b-gray-300">
                <label>Fees, paid by the following, may be collected through PayPal:</label>
                <div class="ml-4 space-y-0">
                    <x-forms.elements.livewire.inputCheckbox
                        blur="false"
                        label="Teacher"
                        name="createForm.teacher"
                        livewire="true"
                    />
                    <x-forms.elements.livewire.inputCheckbox
                        blur="false"
                        label="Student"
                        name="createForm.student"
                        livewire="true"
                    />
                </div>
            </fieldset>

            {{-- Pitch Files --}}
            <fieldset id="pitchFiles" class="space-y-0 pb-4 border border-white border-b-gray-300">
                <label>Pitch files will be available to the students and teachers:</label>
                <div class="ml-4 space-y-0">
                    <x-forms.elements.livewire.inputCheckbox
                        blur="false"
                        label="Pitch files on TheDirectorsRoom.com"
                        name="createForm.pitchFilesteacher"
                        livewire="true"
                    />
                    <x-forms.elements.livewire.inputCheckbox
                        blur="false"
                        label="Pitch files on StudentFolder.info"
                        name="createForm.pitchFilesStudent"
                        livewire="true"
                    />
                </div>
            </fieldset>

            {{-- SUCCESS INDICATOR --}}
            <x-forms.indicators.successIndicator
                :showSuccessIndicator="$showSuccessIndicator"
                message="{{  $successMessage }}"
            />

            <div class="flex flex-row space-x-2">
                {{-- SUBMIT AND RETURN TO TABLE VIEW--}}
                <x-buttons.submit/>
            </div>

        </div>
    </form>
</div>
