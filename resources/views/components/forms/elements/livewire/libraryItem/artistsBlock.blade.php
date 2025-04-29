<div class="border border-gray-400 p-2 mt-2">
    <div class="font-bold mb-2">
        Artists
        <span class="font-normal text-xs italic ">Please use full name as printed. (ex.'John Lennon & Paul McCartney' or 'J. S. Bach')</span>
    </div>
    @if((! isset($form->policies['canEdit']['artists'])) || $form->policies['canEdit']['artists'])
        <div class="flex flex-col space-y-2">

            {{-- COMPOSER, ARRANGER, WAM, WORDS, MUSIC, LYRICIST, CHOREOGRAPHER --}}
            @foreach($artistTypes AS $artistType)
                <x-forms.elements.livewire.libraryItem.artistsBlockItem
                    :artistName="$form->artists[$artistType]"
                    for="{{  $artistType }}"
                    :searchResults="$searchResultsArtists[$artistType]"
                    :canEdit="$form->policies['canEdit'][$artistType]"
                />
            @endforeach

        </div>

    @else
        <div class="border border-gray-600 w-11/12 sm:w-10/12 px-2">
            {{ $form->artistsBlock }}
        </div>
    @endif

</div>
