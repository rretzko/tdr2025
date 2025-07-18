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

        {{-- LIBRARIAN --}}
        <div class="border border-gray-400 p-2">
            <header>
                If you wish to give a student librarian access to this module, please use the following:
            </header>
            <div class="ml-4 text-sm">
                <div class="flex flex-row space-x-2">
                    <label class="w-24">
                        Email
                    </label>
                    <div class="font-semibold">
                        {{ $studentLibrarianEmail }}
                    </div>
                </div>
                <div class="flex flex-row space-x-2">
                    <label class="w-24">
                        Password
                    </label>
                    <div>
                        <div class="font-semibold">
                            {{ $studentLibrarianPassword }}
                        </div>
                        <button
                            wire:click="regenerateLibrarianPassword()"
                            type="button"
                            class="bg-yellow-800 text-white text-sm px-2 rounded-lg shadow-lg"
                        >
                            Reset Librarian Password
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="flex flex-row space-x-2 items-center">
            <x-buttons.submit value="save"/>
            <button wire:click="clickForm" type="button" class="text-sm text-blue-700 mt-2 hover:underline">
                Cancel
            </button>
        </div>
    </form>
</div>
