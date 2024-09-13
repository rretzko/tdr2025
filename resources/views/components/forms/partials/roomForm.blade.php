<form wire:submit="save" class="space-y-4  shadow-lg px-4 pb-4">

    <style>
        .narrow {
            width: 50%;
        }
    </style>
    {{-- SYSID, NAME --}}
    <fieldset class="flex flex-col space-y-2">
        <div class="flex flex-row space-x-2">
            <label class="flex items-center">SysId</label>
            <div class="font-semibold">{{ $form->sysId ?: 'new' }}</div>
        </div>
    </fieldset>

    <fieldset class="flex flex-col space-y-2">
        <x-forms.elements.livewire.inputTextNarrow
            label="room name"
            name="form.roomName"
            wireModel="form.roomName"
        />
    </fieldset>

    <fieldset class="flex flex-col space-y-2">
        <legend for="form.contentTypes" class="text-sm">Content to be auditioned</legend>
        @foreach($contentTypes AS $contentType)
            <div class="flex flex-row space-x-2 ml-2 items-center">
                <input type="checkbox" wire:model.live="form.contentTypeIds" value="{{ $contentType }}">
                <label for="contentTypeIds[$contentType]">{{ ucwords($contentType) }}</label>
            </div>
        @endforeach

    </fieldset>

    <fieldset class="flex flex-col space-y-2">
        <legend for="form.contentTypes" class="text-sm">Voice Parts to be auditioned</legend>
        <div class="flex flex-wrap">
            @foreach($voiceParts AS $voicePart)
                <div class="flex flex-row space-x-2 ml-2 items-center">
                    <input type="checkbox" wire:model.live="form.voicePartIds" value="{{ $voicePart->id }}">
                    <label for="voicePartIds[$voicePartId]">{{ ucwords($voicePart->descr) }}</label>
                </div>
            @endforeach
        </div>

    </fieldset>

    <fieldset class="flex flex-col space-y-2 max-w-fit">
        <label for="tolerance">Room Tolerance</label>
        <select wire:model="form.tolerance">
            @foreach($tolerances AS $tolerance)
                <option value="{{ $tolerance }}">
                    {{ $tolerance }}
                </option>
            @endforeach
        </select>
    </fieldset>

    <fieldset class="flex flex-row space-x-2 pb-2 ">
        <button wire:click="save"
                class="bg-gray-800 text-white rounded-full px-2 text-xs"
                type="submit"
        >
            Save Room
        </button>

        <button wire:click="$toggle('showForm')"
                class="bg-gray-800 text-white rounded-full px-2 text-xs"
                type="button"
        >
            Close Form
        </button>
    </fieldset>

    {{-- SUCCESS INDICATOR --}}
    @if($showSuccessIndicator)
        <div class="text-green-600 italic text-xs">
            {{ $successMessage }}
        </div>
    @endif
</form>
