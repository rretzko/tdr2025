<div class="border border-gray-400 p-2 mt-2">
    <div class="font-bold mb-2">
        Artists
        <span class="font-normal text-xs italic ">Please use full name as printed. (ex.'John Lennon & Paul McCartney' or 'J. S. Bach')</span>
    </div>
    @if((! isset($form->policies['canEdit']['artists'])) || $form->policies['canEdit']['artists'])
        <div class="flex flex-col space-y-2">

            {{-- COMPOSER --}}
            <x-forms.elements.livewire.libraryItem.artistsBlockItem
                for="composer"
                :searchResults="$searchResultsArtists['composer']"
            />

            {{-- ARRANGER --}}
            <x-forms.elements.livewire.libraryItem.artistsBlockItem
                for="arranger"
                :searchResults="$searchResultsArtists['arranger']"
            />

            {{-- WORDS --}}
            <x-forms.elements.livewire.libraryItem.artistsBlockItem
                for="words"
                :searchResults="$searchResultsArtists['words']"
            />

        </div>

    @else
        <div class="border border-gray-600 w-11/12 sm:w-10/12 px-2">
            {{ $form->artistsBlock }}
        </div>
    @endif

</div>
