<div class="border border-gray-200 rounded-lg shadow-lg p-2 my-2 mx-auto w-11/12">
    {{-- NAME --}}
    <div class="space-y-0.5 mb-2">
        <label class="font-semibold">Name</label>
        <div class="flex flex-row items-center">
            <label style="width: 4rem;">First</label>
            <input type="text" wire:model="form.first"/>
            @error('first')
            <div>{{ $errors['first']->message }}</div>
            @enderror
        </div>
        <div class="flex flex-row items-center">
            <label style="width: 4rem;">Middle</label>
            <input type="text" wire:model="form.middle"/>
            @error('middle')
            <div>{{ $errors['middle']->message }}</div>
            @enderror
        </div>
        <div class="flex flex-row items-center">
            <label style="width: 4rem;">Last</label>
            <input type="text" wire:model="form.last"/>
            @error('last')
            <div>{{ $errors['last']->message }}</div>
            @enderror
        </div>
    </div>

    {{-- VOICE PART --}}
    <div class="flex flex-col space-y-0.5 mb-2">
        <label class="font-semibold">Voice Part</label>
        <select wire:model="voicePartId">
            @foreach($eventVoiceParts AS $voicePart)
                <option value="{{ $voicePart->id }}">
                    {{ $voicePart->descr }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- PHONE HOME --}}
    <div class="flex flex-col space-y-0.5 mb-2">
        <label class="font-semibold">Home Phone</label>
        <input type="text" wire:model="form.phoneHome"/>
        @error('phoneHome')
        <div>{{ $errors['phoneHome']->message }}</div>
        @enderror
    </div>

    {{-- MOBILE HOME --}}
    <div class="flex flex-col space-y-0.5 mb-2">
        <label class="font-semibold">Cell Phone</label>
        <input type="text" wire:model="form.phoneMobile"/>
        @error('phoneMobile')
        <div>{{ $errors['phoneMobile']->message }}</div>
        @enderror
    </div>

    <div class=" space-y-0.5 mb-2">
        <button type="button" class="bg-black text-white px-2 rounded shadow-lg" wire:click="saveEdits">
            Save Edits
        </button>
    </div>

</div>
