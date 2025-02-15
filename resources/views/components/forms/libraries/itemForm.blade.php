<div id="itemForm">
    <form wire:submit="save">

        {{-- ITEM TYPE --}}
        <div class="border border-white border-t-black border-b-black">
            <label class="flex flex-row space-x-1">
                <div>Item type:</div>
                <div class="flex flex-wrap">
                    @foreach($itemTypes AS $key => $value)
                        <div
                            class="flex flex-row items-center space-x-2  px-2 mb-1 {{ $loop->odd ? 'bg-gray-300' : 'bg-gray-100' }}">
                            <input type="radio" value="{{ $value }}" wire:model.live="form.itemType">
                            <label>{{ ucwords($key) }}</label>
                        </div>
                    @endforeach
                </div>
            </label>
        </div>

        {{-- FORM AND SEARCH RESULTS --}}
        <div id="content" class="flex flex-col-reverse sm:flex-row mt-2 rounded-lg">

            {{-- FORM --}}
            <div id="inputs" class="w-full bg-gray-100 p-2 rounded-lg sm:w-3/4 ">
                @include($bladeForm)

                @include('components.forms.elements.livewire.libraryItem.saveSaveAndStay')
            </div>

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
                <div id="results" class="bg-gray-300 w-full rounded-lg p-2 mb-1 border ">
                    {{ $searchResults }}
                </div>
            </div>

        </div>

    </form>
</div>
