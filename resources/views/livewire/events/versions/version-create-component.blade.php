<div class="px-4">
    <h2>Add {{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

        <div class="space-y-4">
            <x-forms.styles.genericStyle/>

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


            <fieldset id="user-info">
                <ul>
                    <li>SysId</li>
                    <li>Name</li>
                    <li>Short Name</li>
                    <li>Senior Class</li>
                    <li>Status</li>
                    <li>Upload files</li>
                    <li>Fees:
                        <ul>
                            <li>Registration</li>
                            <li>On-Site Registration</li>
                            <li>Participation</li>
                        </ul>
                    </li>
                    <li>PayPal
                        <ul>
                            <li>Teachers</li>
                            <li>Students</li>
                        </ul>
                    </li>
                    <li>Pitch Files</li>
                </ul>
            </fieldset>
        </div>
    </form>
</div>
