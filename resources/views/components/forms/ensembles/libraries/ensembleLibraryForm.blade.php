<div class="my-2 bg-gray-200 p-2 rounded-lg shadow-lg">
    <form wire:submit="save">

        {{-- TITLE --}}
        <x-forms.elements.livewire.inputTextWide
            :autofocus="true"
            label="Item Title"
            name="form.title"
            :required="true"
            :blur="false"
        />

        <div>
            @forelse($titleSearchResults AS $item)
                <button wire:click="setItem({{ $item->id }})" class="text-sm text-blue-600">
                    {{ $item->title }}
                </button>
            @empty
                <div></div>
            @endforelse
        </div>

        {{-- PROGRAM --}}
        <x-forms.elements.livewire.selectWide
            label="program"
            name="form.program"
            :options="$programs"
            required="true"
        />

        {{-- STATUS --}}
        <x-forms.elements.livewire.selectWide
            label="status"
            name="form.status"
            :options="$statuses"
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
