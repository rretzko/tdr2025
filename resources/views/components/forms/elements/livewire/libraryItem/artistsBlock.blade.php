<div class="border border-gray-400 p-2 mt-2">
    <div class="font-bold mb-2">
        @if($form->itemType === 'book')
            Author
        @else
            Artists
        @endif
        <span class="font-normal text-xs italic ">
            @if($form->itemType === 'book')
                Please use full name as printed. (ex.'Arnold Fish and Norman Lloyd')
            @else
                Please use full name as printed. (ex.'John Lennon & Paul McCartney' or 'J. S. Bach')
            @endif
        </span>
    </div>
    @if((! isset($form->policies['canEdit']['artists'])) || $form->policies['canEdit']['artists'])
        <div class="flex flex-col space-y-2">

            @if($form->itemType === 'book')
                <x-forms.elements.livewire.libraryItem.artistsBlockItem
                    :artistName="$form->artists['author']"
                    for="author"
                    :searchResults="$searchResultsArtists['author']"
                    :canEdit="$form->policies['canEdit']['author']"
                />
            @else

                {{-- COMPOSER, ARRANGER, WAM, WORDS, MUSIC, LYRICIST, CHOREOGRAPHER --}}
                @foreach($artistTypes AS $artistType)
                    {{--@if($searchResultsArtists[$artistType]) @dd($artistType) @endif--}}
                    <x-forms.elements.livewire.libraryItem.artistsBlockItem
                        :artistName="$form->artists[$artistType]"
                        for="{{  $artistType }}"
                        :searchResults="$searchResultsArtists[$artistType]"
                        :canEdit="$form->policies['canEdit'][$artistType]"
                    />
                @endforeach

            @endif

        </div>

    @else
        <div class="border border-gray-600 w-11/12 sm:w-10/12 px-2">
            {{ $form->artistsBlock }}
        </div>
    @endif

</div>
