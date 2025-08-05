<div class="">
    <label for="form.title">
        <div>Title</div>
        @if((! isset($form->policies['canEdit']['title'])) || $form->policies['canEdit']['title'])
            <input
                type="text"
                wire:model.live.debounce="form.title"
                class="w-11/12 sm:w-10/12"
                autofocus
            />
        @else
            <div class="border border-gray-600 w-11/12 sm:w-10/12 px-2">
                {{ $form->title }}
            </div>
        @endif
    </label>
    @error('form.title')
    <x-input-error messages="{{ $message }}" aria-live="polite"/>
    @enderror
</div>
