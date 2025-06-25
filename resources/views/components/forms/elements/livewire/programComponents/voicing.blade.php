<div class="">
    <label for="form.voicing">
        @if((! isset($form->policies['canEdit']['voicing'])) || $form->policies['canEdit']['voicing'])
            <input
                type="text"
                wire:model.blur="voicing"
                class="w-11/12 sm:w-10/12"
            />
        @else
            <div class="border border-gray-600 w-11/12 sm:w-10/12 px-2">
                {{ $form->voicing }}
            </div>
        @endif
    </label>
</div>
