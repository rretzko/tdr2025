<div class="px-4">
    <h2>{{ ucwords($header) }}: {{ $fullName }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">

        {{-- TABS --}}
        <x-tabs.studentEditTabs :tabs="$tabs" :selected-tab="$selectedTab"/>

        {{-- FORM --}}
        <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

            <div class="space-y-4">
                <x-forms.styles.genericStyle/>

                {{-- SYS ID --}}
                <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" data="$sysId" wireModel="sysId"/>

                <fieldset id="email">
                    <x-forms.elements.livewire.inputTextWide
                        label="email"
                        name="form.email"
                        type="email"
                    />
                </fieldset>

                @if($successMessageEmail)
                    <div class="text-green-600 italic text-xs">
                        {{ $successMessageEmail }}
                    </div>
                @endif

                <fieldset id="phones">
                    <div>
                        <x-forms.elements.livewire.inputTextWide
                            label="Cell Phone"
                            name="form.phoneMobile"
                        />
                    </div>
                    <div>
                        <x-forms.elements.livewire.inputTextWide
                            label="Home Phone"
                            name="form.phoneHome"
                        />
                    </div>

                </fieldset>

                @if($successMessagePhones)
                    <div class="text-green-600 italic text-xs">
                        {{ $successMessagePhones }}
                    </div>
                @endif

                <fieldset id="address">
                    <div>
                        {{-- ADDRESS 1 --}}
                        <x-forms.elements.livewire.inputTextWide
                            label="Home Address"
                            name="form.address1"
                            placeholder="address 1"
                        />

                        {{-- ADDRESS 2 --}}
                        <x-forms.elements.livewire.inputTextWide
                            label="Address"
                            name="form.address2"
                            placeholder="address 2"
                            suppressLabel="true"
                        />

                        {{-- CITY, STATE POSTAL CODE --}}
                        <div class="flex flex-col md:flex-row md:space-x-2">

                            {{-- CITY --}}
                            <x-forms.elements.livewire.inputTextWide
                                label="city"
                                name="form.city"
                            />

                            {{-- GEOSTATE --}}
                            <x-forms.elements.livewire.selectNarrow
                                label="state"
                                name="form.geostate_id"
                                :options="$geostates"
                            />

                            {{-- POSTAL CODE --}}
                            <div class="flex flex-row space-x-2">
                                <x-forms.elements.livewire.inputTextNarrow
                                    label="zip code"
                                    name="form.postalCode"
                                />
                            </div>

                        </div>

                    </div>
                </fieldset>

                @if($successMessageAddress)
                    <div class="text-green-600 italic text-xs">
                        {{ $successMessageAddress }}
                    </div>
                @endif

            </div>
        </form>

    </div>{{-- END OF ID=CONTAINER --}}

</div>

