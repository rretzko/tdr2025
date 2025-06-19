<div class="border border-transparent border-b-gray-300 mb-2">
    <label>
        Please use the checkboxes below to indicate that this selection
        opens or closes the ensemble segment.
    </label>
    <div class="flex flex-row align-middle justify-left space-x-4 ml-2">

        {{-- OPENER --}}
        <div>
            <label class="space-x-2 align-middle">
                <input type="checkbox" wire:model="form.opener">
                Opener
            </label>
        </div>

        {{-- CLOSER --}}
        <div>
            <label class="space-x-2 align-middle">
                <input type="checkbox" wire:model="form.closer">
                Closer
            </label>
        </div>
    </div>
</div>
