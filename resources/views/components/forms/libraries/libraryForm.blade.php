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

        {{-- HOME LIBRARY LINK --}}
        @if($form->name !== 'Home Library')
            <div class="border border-gray-400 p-2 mb-2">
                <h2>
                    <b>Perusal Copies</b>
                    <span class="ml-2 text-sm italic">See "Page Instructions" above for further information.</span>
                </h2>
                <div class="mb-2">
                    A perusal copy should be automatically added to
                    my "Home Library" whenever a new item is added for:
                </div>

                <div class="flex flex-wrap space-x-4 ml-4">
                    @foreach(\App\Enums\ItemType::cases() AS $case)
                        <div class="flex items-center space-x-1 mb-1 sm:mb-0">
                            <input type="checkbox" wire:model="form.perusalItemTypes" value="{{ $case }}"
                                   id="{{  $case }}"/>
                            <label for="{{ $case }}">
                                {{ $case }}
                            </label>

                        </div>
                    @endforeach
                </div>
                <div>
                    using the following location identifier:
                </div>
                <div class="flex flex-row space-x-4 ml-4">
                    <div class="flex items-center space-x-1">
                        <input type="radio" wire:model="form.perusalUseItemId" value="1">
                        <label for="itemId">
                            Item Id
                        </label>
                    </div>
                    <div class="flex items-center space-x-1">
                        <input type="radio" wire:model="form.perusalUseItemId" value="0">
                        <label for="itemLocations">
                            Item location fields
                        </label>
                    </div>
                </div>
            </div>
        @endif

        {{-- STUDENT LIBRARIAN --}}
        <div class="border border-gray-400 p-2">
            @if($studentLibrarianEmail)
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
            @else
                <div class="text-sm">
                    Student librarian credentials will be available here after you click the "Save" button.
                </div>
            @endif
        </div>

        <div class="flex flex-row space-x-2 items-center">
            <x-buttons.submit value="save"/>
            <button wire:click="clickForm" type="button" class="text-sm text-blue-700 mt-2 hover:underline">
                Cancel
            </button>
        </div>
    </form>
</div>
