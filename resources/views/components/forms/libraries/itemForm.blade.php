<div id="itemForm">

    <form wire:submit="save">

        {{-- ITEM TYPE --}}
        <div class="border border-white border-t-black border-b-black">
            <label class="flex flex-row space-x-1">
                <div>Item type:</div>
                <div class="flex flex-wrap">

                @foreach(\App\Enums\ItemType::cases() AS $case)
                        <div
                            class="flex flex-row items-center space-x-2  px-2 {{ $loop->odd ? 'bg-gray-300' : 'bg-gray-100' }}">
                            <input
                                type="radio"
                                value="{{ $case }}"
                                wire:model.live="form.itemType"
                                {{-- @disabled(isset($form->policies['canEdit']['itemType']) && (! $form->policies['canEdit']['itemType'])) --}}
                            >
                            <label>{{ ucwords($case->value) }}</label>
                        </div>
                    @endforeach
                </div>
            </label>
        </div>

        {{-- FORM AND SEARCH RESULTS --}}
        <div id="content" class="flex flex-col-reverse sm:flex-row mt-2 rounded-lg">

            {{-- FORM --}}
            <div id="inputs"
                @class([
                   "w-full $form->backgroundColor p-2 rounded-lg",
                   "sm:w-3/4 " => (!$form->sysId)
               ])
            >
                @include($bladeForm)

                {{-- ADD TO HOME LIBRARY --}}
                @if($libraryName !== "Home Library")
                    <div class="flex space-x-2 my-2 px-2 bg-gray-300">
                        <input type="checkbox" wire:model="form.addToHomeLibrary" id="addToHomeLibrary" class="mt-2"/>
                        <label for="addToHomeLibrary">
                            @if($form->sysId)
                                Update
                            @else
                                Add
                            @endif this {{ $form->itemType }} to my Home Library.<br/>
                            @if($form->sysId)
                                NOTE: This will overwrite any changes you've made to this {{ $form->itemType }}
                                in your Home Library records!
                            @endif
                        </label>
                    </div>
                @endif

                @if($form->sysId)
                    @include('components.forms.elements.livewire.libraryItem.save')
                @else
                    @include('components.forms.elements.livewire.libraryItem.saveSaveAndStay')
                @endif
            </div>

            {{-- SUCCESS/ERROR MESSAGES AND SEARCH RESULTS --}}
            <div class="flex flex-col w-full sm:w-1/4 sm:ml-1 sm:mb-0">

                {{-- SUCCESS MESSAGE --}}
                <div class="bg-green-200 text-green-900 rounded-lg mb-1 px-2 ">
                    {{ $successMessage }}
                </div>

                {{-- ERROR MESSAGE --}}
                <div class="bg-red-200 text-red-900 rounded-lg mb-1 px-2 ">
                    {{ $errorMessage }}
                </div>

                {{-- SEARCH RESULTS --}}
                <div id="results"
                    @class([
                       "bg-gray-300 w-full rounded-lg p-2 mb-1 border ",
                       'hidden' => $form->sysId, //hide if editing library item
                   ])
                >
                    {!! $searchResults !!}
                </div>
            </div>

        </div>

    </form>
</div>
