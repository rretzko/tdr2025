<div class="px-4">
    <h2>{{ ucwords($header) }}: {{ $fullName }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">

        {{-- TABS --}}
        <x-tabs.studentEditTabs :tabs="$tabs" :selected-tab="$selectedTab"/>

        <div id="tableAndForm">

            {{-- TABLE --}}
            <div class="my-4">
                <table class="px-4 shadow-lg w-full">
                    <thead>
                    <tr>
                        <th class="border border-gray-200 px-1 ">
                            Name
                        </th>
                        <th class="border border-gray-200 px-1 ">
                            Relationship
                        </th>
                        <th class="border border-gray-200 px-1 ">
                            Email
                        </th>
                        <th class="border border-gray-200 px-1 text-blue-500"
                            title="Best phone highlighted"
                        >
                            Phones
                        </th>
                        <th class="border border-transparent px-1 sr-only">
                            Edit
                        </th>
                        <th class="border border-transparent px-1 sr-only">
                            Remove
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($rows AS $row)
                        <tr class=" odd:bg-green-100 ">
                            <td class="border border-gray-200 px-1">
                                {{ $row['name'] }}
                            </td>
                            <td class="border border-gray-200 px-1 text-center">
                                {{ $row['emergency_contact_type_id'] }}
                            </td>
                            <td class="border border-gray-200 px-1 text-center">
                                {{ $row['email'] }}
                            </td>
                            <td class="border border-gray-200 px-1 text-center">
                                <div
                                    @class([
                                        'text-gray-400',
                                        'font-extrabold, text-green-600' => ($row['best_phone'] === 'mobile')
                                    ])
                                >
                                    {{ $row['phone_mobile'] ? $row['phone_mobile'] . ' (c)' : ''}}
                                </div>
                                <div
                                    @class([
                                        'text-gray-400',
                                        'font-extrabold, text-green-600' => ($row['best_phone'] === 'home')
                                    ])
                                >
                                    {{ $row['phone_home'] ? $row['phone_home'] . ' (h)' : '' }}
                                </div>
                                <div
                                    @class([
                                        'text-gray-400',
                                        'font-extrabold, text-green-600' => ($row['best_phone'] === 'work')
                                    ])
                                >
                                    {{ $row['phone_work'] ? $row['phone_work'] . ' (w)' : '' }}
                                </
                                >
                            </td>
                            <td class="text-center border border-gray-200">
                                <x-buttons.edit id="{{ $row['id'] }}" livewire="1"/>
                            </td>
                            <td class="text-center border border-gray-200">
                                <x-buttons.remove id="{{ $row['id'] }}" livewire="1"/>
                            </td>
                        </tr>

                    @empty
                        <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                            No emergency contacts found.
                        </td>
                    @endforelse
                    </tbody>
                </table>
            </div>{{-- END OF TABLE DIV --}}

            {{-- FORM --}}
            <div>
                <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

                    <div class="space-y-4">
                        <x-forms.styles.genericStyle/>

                        {{-- SYS ID --}}
                        <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" data="$ecForm.sysId"
                                                                   wireModel="ecForm.sysId"/>

                        {{-- RELATIONSHIP --}}
                        <fieldset id="relationship">
                            <x-forms.elements.livewire.selectNarrow
                                label="relationship"
                                name="ecForm.emergencyContactTypeId"
                                :options="$emergencyContactTypes"
                            />
                        </fieldset>

                        {{-- NAME --}}
                        <fieldset id="name">
                            <x-forms.elements.livewire.inputTextWide
                                label="Name"
                                name="ecForm.name"
                                autofocus="true"
                            />
                        </fieldset>

                        {{-- EMAIL --}}
                        <fieldset id="email">
                            <x-forms.elements.livewire.inputTextWide
                                label="email"
                                name="ecForm.email"
                                type="email"
                            />
                        </fieldset>

                        {{-- PHONES --}}
                        <fieldset id="phones">

                            {{-- MOBILE PHONE --}}
                            <fieldset id="mobilePhone" class="mt-4">
                                <div class="flex flex-col">
                                    <div class="flex flex-row space-x-2">
                                        <label for="ecForm.phoneMobile" class="w-20">Cell Phone</label>
                                        <input type="radio" tabindex="-1" wire:model="ecForm.bestPhone" value="mobile"
                                               class="mt-1">
                                        <label>Best Phone</label>
                                    </div>
                                    <input type="text" class="narrow" wire:model.blue="ecForm.phoneMobile"
                                           aria-label="Cell Phone"/>
                                </div>

                                {{-- ERROR --}}
                                @error('ecForm.phoneMobile')
                                <x-input-error messages="{{ $message }}" aria-live="polite"/>
                                @enderror
                            </fieldset>

                            {{-- HOME PHONE --}}
                            <fieldset id="homePhone" class="mt-4">
                                <div class="flex flex-col">
                                    <div class="flex flex-row space-x-2">
                                        <label for="ecForm.phoneHome" class="w-20">Home</label>
                                        <input type="radio" tabindex="-1" wire:model="ecForm.bestPhone" value="home"
                                               class="mt-1">
                                        <label>Best</label>
                                    </div>
                                    <input type="text" class="narrow" wire:model.blue="ecForm.phoneHome"
                                           aria-label="Home Phone"/>
                                </div>

                                {{-- ERROR --}}
                                @error('ecForm.phoneHome')
                                <x-input-error messages="{{ $message }}" aria-live="polite"/>
                                @enderror
                            </fieldset>

                            {{-- WORK PHONE --}}
                            <fieldset id="workPhone" class="mt-4">
                                <div class="flex flex-col">
                                    <div class="flex flex-row space-x-2">
                                        <label for="ecForm.phoneWork" class="w-20">Work</label>
                                        <input type="radio" tabindex="-1" wire:model="ecForm.bestPhone" value="work"
                                               class="mt-1">
                                        <label>Best</label>
                                    </div>
                                    <input type="text" class="narrow" wire:model.blue="ecForm.phoneWork"
                                           aria-label="Work Phone"/>
                                </div>

                                {{-- ERROR --}}
                                @error('ecForm.phoneWork')
                                <x-input-error messages="{{ $message }}" aria-live="polite"/>
                                @enderror
                            </fieldset>

                        </fieldset>

                        {{-- SUBMIT --}}
                        <div class="flex flex-col mt-2 max-w-xs">
                            <button type="submit"
                                    class="bg-gray-800 text-white px-2 rounded-full disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                Submit
                            </button>
                        </div>

                        {{-- SUCCESS INDICATOR --}}
                        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                                             message="{{  $successMessage }}"/>
                    </div>

            </div>
            </form>{{-- END OF FORM --}}
        </div>{{-- END OF FORM DIV --}}

    </div>{{-- END OF ID=CONTAINER --}}

</div>


