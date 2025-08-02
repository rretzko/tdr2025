<div>
    {{-- BOOK TYPES --}}
    @include('components.forms.elements.livewire.libraryItem.bookTypes')

    {{-- TITLE --}}
    @include('components.forms.elements.livewire.libraryItem.title')

    {{-- VOICING --}}
    @if($form->bookType === 'music')
        @include('components.forms.elements.livewire.libraryItem.voicings')
    @endif

    {{-- COUNT --}}
    @include('components.forms.elements.livewire.libraryItem.count')

    {{-- ARTISTS --}}
    @include('components.forms.elements.livewire.libraryItem.artistsBlock')

    @unless($form->itemType === 'book' && $form->bookType === 'text')
        @include('components.forms.elements.livewire.libraryItem.medleySelections')
    @endunless

    {{-- TAGS --}}
    @include('components.forms.elements.livewire.libraryItem.tags')

    {{-- COMMENTS AND RATING --}}
    @if(auth()->user()->isTeacher())
        @unless($form->itemType === 'book' && $form->bookType === 'text')
            @include('components.forms.elements.livewire.libraryItem.commentsRatings')
        @endunless
    @endif

    {{-- LOCATION --}}
    @include('components.forms.elements.livewire.libraryItem.location')

</div>

