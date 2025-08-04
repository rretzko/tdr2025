<div>
    @php($moduleName="digital library items")
    @include('components.underConstructions.underConstructionApp')
    {{--    <x-underConstructions.underConstructionApp moduleName="digital library items" libraryId="{{ $form->libraryId }}"/>--}}
    {{-- TITLE --}}
    {{--    @include('components.forms.elements.livewire.libraryItem.title')--}}

    {{-- VOICING --}}
    {{--    @include('components.forms.elements.livewire.libraryItem.voicings')--}}

    {{-- COUNT & PRICE --}}
    {{--    @include('components.forms.elements.livewire.libraryItem.count')--}}

    {{-- ARTISTS --}}
    {{--    @include('components.forms.elements.livewire.libraryItem.artistsBlock')--}}

    {{-- TAGS --}}
    {{--    @include('components.forms.elements.livewire.libraryItem.tags')--}}

    {{-- COMMENTS AND RATING --}}
    @if(auth()->user()->isTeacher())
        {{--        @include('components.forms.elements.livewire.libraryItem.commentsRatings')--}}
    @endif

    {{-- LOCATION --}}
    {{--    @include('components.forms.elements.livewire.libraryItem.location')--}}

</div>
