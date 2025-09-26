<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH --}}
    @if($hasSearch)
        <div class="px-4 w-11/12">
            <input class="w-3/4" type="text" placeholder="Search"/>
        </div>
    @endif

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- IMPORT LINKS --}}
        <div class="text-sm text-right">
            <button
                type="button"
                wire:click="clickImportItems"
                class="text-blue-600"
            >
                Import Items via .csv file
            </button>
        </div>

        @if(auth()->id() === 45) {{-- limited to Barbara Retzko --}}
            <div class="text-sm text-right">
                <button
                    type="button"
                    wire:click="clickAddViaImageOrPdf"
                    class="text-blue-600"
                    >
                    Add via Image or PDF
                </button>
            </div>
        @endif

        {{-- HEADER and ADD-NEW BUTTON --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }} for <span class="font-bold">{{ $library->name }}</span></div>
        </div>

        {{-- ITEM ADD VIA IMAGE or PDF FORM --}}
        @if($displayViaImageOrPdf)
            <div class="mb-2 ">
                @include('components.forms.elements.livewire.libraryItem.addViaImageOrPdfForm')
            </div>
        @endif

        {{-- FILE IMPORT FORM --}}
        @if($displayFileImportForm)
            <div class="mb-2 ">
                @include('components.forms.elements.livewire.libraryItem.uploadLibraryItemsForm')
            </div>
        @endif

        {{-- FORM --}}
        <div class="flex flex-col">
            @include('components.forms.libraries.itemForm')
        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>{{-- END OF PAGE CONTENT --}}

</div>







