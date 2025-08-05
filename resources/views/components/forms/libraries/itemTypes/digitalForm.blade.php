<div>
    @php($moduleName="digital library items")

    {{-- TITLE --}}
    @include('components.forms.elements.livewire.libraryItem.title')

    {{-- https://www.youtube.com/watch?v=IDQn6KP94xs --}}

    {{-- DIGITAL URL --}}
    <div class="flex flex-col ">
        <label for="digitalUrl">
            Web Address
        </label>
        <input
            type="url"
            wire:model="form.digitalUrl"
            class="w-5/6"
            placeholder="@if($form->sysId == 0) Enter title above to select a library item from search results.. @endif"
            @disabled($form->sysId == 0)
        />
        @error('form.digitalUrl')
        <x-input-error messages="{{ $message }}" aria-live="polite"/>
        @enderror
    </div>
    {{-- DIGITAL URL LABEL--}}
    <div class="flex flex-col ">
        <label for="digitalUrl">
            Label to identify link
        </label>
        <input
            type="text"
            wire:model="form.digitalUrlLabel"
            class="w-5/6"
            placeholder="@if($form->sysId)Enter label to identify this link on mouse-hover... @endif"
            @disabled($form->sysId == 0)
        />
        @error('form.digitalUrlLabel')
        <x-input-error messages="{{ $message }}" aria-live="polite"/>
        @enderror
    </div>
</div>
