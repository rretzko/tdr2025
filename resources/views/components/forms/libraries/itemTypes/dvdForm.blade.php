<div>
    {{-- TITLE --}}
    @include('components.forms.elements.livewire.libraryItem.title')

    {{-- VOICING --}}
    @include('components.forms.elements.livewire.libraryItem.voicings')

    {{-- COUNT --}}
    @include('components.forms.elements.livewire.libraryItem.count')

    {{-- ARTISTS --}}
    @include('components.forms.elements.livewire.libraryItem.artistsBlock')

    @include('components.forms.elements.livewire.libraryItem.medleySelections')

    {{-- TAGS --}}
    @include('components.forms.elements.livewire.libraryItem.tags')

    {{-- COMMENTS AND RATING --}}
    @if(auth()->user()->isTeacher())
        @include('components.forms.elements.livewire.libraryItem.commentsRatings')
    @endif

    {{-- LOCATION --}}
    @include('components.forms.elements.livewire.libraryItem.location')

</div>

