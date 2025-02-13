<div class="px-4">
    <h2>Add {{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

        <div class="space-y-4">
            <x-forms.styles.genericStyle/>

            <div class="text-right mr-4">
                <a href="{{ route('inventory.create') }}" class="text-blue-500 hover:underline">
                    Single Add
                </a>
            </div>

            <div class="flex flex-col ">

                {{-- SCHOOL ENSEMBLE INVENTORY FIELDS --}}
                <div id="schoolEnsembleInventoryDefinition">

                    {{-- SYS ID --}}
                    <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" wireModel="form.sysId"/>

                    {{-- ENSEMBLE --}}
                    <x-forms.elements.livewire.selectWide
                        :autofocus="true"
                        label="ensemble"
                        name="form.ensembleId"
                        :options="$ensembles"
                        required="required"
                    />

                    {{-- ASSETS --}}
                    <x-forms.elements.livewire.selectWide
                        label="asset"
                        name="form.assetId"
                        :options="$assets"
                        required="required"
                    />

                    {{-- ASSET COUNT --}}
                    <x-forms.elements.livewire.selectNarrow
                        label="How many {{ $form->assetNamePlural }} do you want to add?"
                        name="form.assetCount"
                        :options="$oneToThreeHundred"
                        required="required"
                    />

                    {{-- ITEM ID STARTING POINT --}}
                    {{--                    <x-forms.elements.livewire.inputTextNarrow--}}
                    {{--                        label="The first id number for these {{  $form->assetNamePlural }} should be: "--}}
                    {{--                        name="form.assetIdStartingPoint"--}}
                    {{--                        required="required"--}}
                    {{--                        type="number"--}}
                    {{--                        hint="This must be a numeric value."--}}
                    {{--                    />--}}

                    {{-- ITEM SIZE --}}
                    <x-forms.elements.livewire.inputTextNarrow
                        label="size"
                        name="form.size"
                        hint="Leave blank if size is unimportant."
                    />

                    {{-- COLOR(S) --}}
                    <x-forms.elements.livewire.inputTextNarrow
                        label="color"
                        name="form.color"
                        hint="Leave blank if color is unimportant."
                    />

                    {{-- STATUS --}}
                    <x-forms.elements.livewire.selectNarrow
                        label="status"
                        name="form.status"
                        option0
                        :options="$statuses"
                        required="required"
                    />

                    {{-- COMMENTS --}}
                    <x-forms.elements.livewire.inputTextWide
                        hint='Add any helpful comments...'
                        label='comments'
                        name='form.comments'
                    />

                    {{-- CREATOR --}}
                    <div class="text-xs text-indigo-600 mt-2">
                        Inventory item added by: {{ $form->creator }}
                    </div>

                </div>

            </div>

            <div class="flex flex-row space-x-2">
                {{-- SUBMIT AND RETURN TO TABLE VIEW--}}
                <x-buttons.submit value="save"/>

                {{-- SUBMIT AND STAY --}}
                <x-buttons.submitAndStay value="save and add another"/>

            </div>

            {{-- SUCCESS INDICATOR --}}
            <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                                 message="{{  $successMessage }}"/>
        </div>
    </form>
    <div>
        {{ $successMessage }}
    </div>
</div>


