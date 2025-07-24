<div class="">
    <label for="form.count">
        <div>Available Copies</div>
        @if((! isset($form->policies['canEdit']['count'])) || $form->policies['canEdit']['count'])
            <input
                type="text"
                wire:model.live.debounce="form.count"
                class="w-1/6 sm:w-1/12"
                autofocus
            />
        @else
            <div class="border border-gray-600 w-1/6 sm:w-1/12 px-2">
                {{ $form->count }}
            </div>
        @endif
    </label>
</div>
