<div class="">
    <label for="form.ensembleId">
        @if((! isset($form->policies['canEdit']['ensembleName'])) || $form->policies['canEdit']['ensembleName'])
            <input
                type="text"
                {{-- wire:model.blur="form.ensembleName" --}}
                wire:model.blue="ensembleName"
                class="w-11/12 sm:w-10/12"
                placeholder="new ensemble"
            />
        @else
            <div class="border border-gray-600 w-11/12 sm:w-10/12 px-2">
                {{ $form->ensembleName }}
            </div>
        @endif
    </label>
</div>
