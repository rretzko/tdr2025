<div class="flex flex-row space-x-2">

    <div id="count">
        <label for="form.count">
            <div>Available Copies</div>
            @if((! isset($form->policies['canEdit']['count'])) || $form->policies['canEdit']['count'])
                <input
                    type="text"
                    wire:model="form.count"
                    class="w-fit "
                />
            @else
                <div class="border border-gray-600 w-1/6 sm:w-1/12 px-2">
                    {{ $form->count }}
                </div>
            @endif
        </label>
    </div>

    <div id="price">
        <label for="form.price">
            <div>Current Price</div>
            @if((! isset($form->policies['canEdit']['price'])) || $form->policies['canEdit']['price'])
                <input
                    type="text"
                    wire:model="form.price"
                    class="w-fit"
                />
            @else
                <div class="border border-gray-600 w-1/6 sm:w-1/12 px-2">
                    ${{ $form->price }}
                </div>
            @endif
        </label>
    </div>
</div>
