<div>
    {{-- DISPLAY FORM IN EDIT MODE --}}
    <x-programs.programSelectionProfile
        artistBlock="{!! $form->programSelection->artistBlock !!}"
        title="{{ $form->programSelection->title }}"
        voicing="{{ $form->voicing }}"
    />

    {{-- OPENER/CLOSER SWITCHES --}}
    @include('components.forms.elements.livewire.programComponents.openerCloser')

    {{-- ADDENDUMS --}}
    @include('components.forms.elements.livewire.programComponents.addendums')

    {{-- BUTTONS --}}
    <div class="flex flex-col space-y-1">

        {{-- SUBMIT --}}
        <x-buttons.submit
            type="button"
            :livewire=true
            wireClick="updateProgramSelection"
            value="update concert Selection"
        />

        {{-- REMOVE CURRENT CONCERT SELECTION --}}
        <div class="w-32">
            <x-buttons.remove
                id="{{$form->programSelectionId}}"
                :livewire=true
                message='Are you sure you want to remove this concert selection?'
            />
        </div>

        {{-- CLEAR FORM --}}
        <button wire:click="resetFormToAdd()" class="text-left text-blue-500 ml-4">
            Clear Form
        </button>


    </div>{{-- end of buttons --}}

</div>{{-- end of editProgramSelection --}}
