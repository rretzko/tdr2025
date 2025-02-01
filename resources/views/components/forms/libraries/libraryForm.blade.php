<div class="my-2 bg-gray-200 p-2 rounded-lg shadow-lg">
    <form wire:submit="save">

        {{-- LIBRARY NAME --}}
        <x-forms.elements.livewire.inputTextWide
            :autofocus="true"
            label="Library Name"
            name="form.name"
            :required="true"
        />

        {{-- SCHOOLS --}}
        <x-forms.elements.livewire.selectWide
            label="school"
            name="form.schoolId"
            :options="$schools"
            required="true"
        />


        <div class="flex flex-row space-x-2 items-center">
            <x-buttons.submit value="save"/>
            <button wire:click="clickForm" type="button" class="text-sm text-blue-700 mt-2 hover:underline">
                Cancel
            </button>
        </div>
    </form>
</div>
