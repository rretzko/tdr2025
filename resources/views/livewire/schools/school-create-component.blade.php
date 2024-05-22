<div class="px-4">
    <h2>{{ $header }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

        <div class="space-y-4">
            <x-forms.styles.genericStyle/>

            {{-- SYS ID --}}
            <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" data="new"/>

            {{-- POSTAL CODE --}}
            <x-forms.elements.livewire.inputTextNarrow label="zip code"
                                                       name="postalCode"
                                                       required
                                                       :results="$resultsPostalCode"
            />

        </div>

        <ul class="mt-8">
            <li>
                name
            </li>
            <li>
                city
            </li>
            <li>
                county
            </li>
            <li>
                grades in school
            </li>
            <li>
                grades taught
            </li>
            <li>
                work email address
            </li>
            <li>
                email verified checkbox?
            </li>
            <li>
                submit button
            </li>
        </ul>
    </form>
</div>
