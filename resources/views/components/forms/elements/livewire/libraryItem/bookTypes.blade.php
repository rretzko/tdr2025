<div class="">
    <label for="form.bookType">
        <div>Book Type</div>
        @if((! isset($form->policies['canEdit']['bookType'])) || $form->policies['canEdit']['bookType'])
            <div class="flex items-center space-x-4 ml-4">
                <div class="space-x-2">
                    <input type="radio" wire:model.live="form.bookType" id="bookTypeMusic" value="music">
                    <label for="bookTypeMusic">
                        Music
                    </label>
                </div>
                <div class="space-x-2">
                    <input type="radio" wire:model.live="form.bookType" id="bookTypeText" value="text">
                    <label for="bookTypeText">
                        Text
                    </label>
                </div>
            </div>
        @else
            <div class="border border-gray-600 w-11/12 sm:w-10/12 px-2">
                {{ $form->bookType }}
            </div>
        @endif
    </label>
</div>
