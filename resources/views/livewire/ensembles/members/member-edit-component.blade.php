<div class="px-4">
    <h2>Add {{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>


    <div class="flex flex-col md:flex-row">

        {{-- DATA FORM --}}
        <form wire:submit="save" class="my-4 mr-2 p-4 border border-gray-200 rounded-lg ">

            <div class="space-y-4">
                <x-forms.styles.genericStyle/>

                <div class="flex flex-col ">

                    {{-- SCHOOL ENSEMBLE MEMBER FIELDS --}}
                    <div id="schoolEnsembleMemberDefinition">

                        {{-- SYS ID --}}
                        <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" wireModel="form.sysId"/>

                        {{-- SCHOOL NAME --}}
                        <x-forms.elements.livewire.labeledInfoOnly
                            label="school"
                            wireModel="form.schoolName"
                        />

                        {{-- ENSEMBLE NAME --}}
                        <x-forms.elements.livewire.labeledInfoOnly
                            label="ensemble"
                            wireModel="form.ensembleName"
                        />

                        {{-- NAME --}}
                        <x-forms.elements.livewire.labeledInfoOnly
                            label="member name"
                            wireModel="form.name"
                        />

                        <x-forms.elements.livewire.labeledInfoOnly
                            label="grade/class of"
                            wireModel="form.classOfGrade"
                        />
                        @if($errors->any())
                            {{ implode('', $errors->all('<div>:message</div>')) }}
                        @endif

                        <input type="hidden" wire:model="form.name" value="{{  $form->name }}"/>

                        {{-- SCHOOL YEAR --}}
                        <x-forms.elements.livewire.inputTextNarrow
                            label="School Year"
                            name="form.schoolYear"
                            required
                            hint="Enter the school year for this member (ex. 2024-25 = 2025)."
                        />

                        {{-- VOICE PARTS --}}
                        <x-forms.elements.livewire.selectWide
                            label="voice part"
                            name="form.voicePartId"
                            :options="$voiceParts"
                            required="required"
                        />

                        {{-- OFFICES --}}
                        <x-forms.elements.livewire.selectWide
                            label="office"
                            name="form.office"
                            :options="$offices"
                            required="required"
                        />

                        {{-- STATUS --}}
                        <x-forms.elements.livewire.selectWide
                            label="status"
                            name="form.status"
                            :options="$statuses"
                            required="required"
                        />

                    </div>

                </div>

                <div class="flex flex-row space-x-2">
                    {{-- SUBMIT AND RETURN TO TABLE VIEW--}}
                    <x-buttons.submit value="update"/>

                </div>

                {{-- SUCCESS INDICATOR --}}
                <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                                     message="{{  $successMessage }}"/>
            </div>
        </form>

        {{-- ASSET ASSIGNMENT FORM --}}
        {{--        @if(count($assets))--}}
        {{--            <div class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">--}}

        {{--                <h3>Asset Assignments</h3>--}}
        {{--                @forelse($availableAssets AS $label => $inventories)--}}
        {{--                    <div class="flex flex-col py-2">--}}
        {{--                        <label>{{ ucwords($label) }}</label>--}}
        {{--                        --}}{{-- display label if member has asset assigned --}}
        {{--                        @if(array_key_exists($label, $form->memberAssets))--}}
        {{--                            <div class="font-bold">--}}
        {{--                                {{ ucwords($form->memberAssets[$label]['label']) }}--}}
        {{--                                <x-buttons.remove livewire="1" id="{{ $form->memberAssets[$label]['id'] }}"/>--}}
        {{--                            </div>--}}
        {{--                            --}}{{-- else display drop-down select if there are many $inventories --}}
        {{--                        @elseif(count($inventories))--}}
        {{--                            <select wire:model.live="assignedAssetId">--}}
        {{--                                <option value="{{ $inventories[0]['asset_id'] }}-0">- select -</option>--}}
        {{--                                @foreach($inventories as $item)--}}

        {{--                                    <option value="{{ $item['asset_id'] }}-{{ $item['id'] }}">--}}
        {{--                                        #{{ $item['item_id'] }}--}}
        {{--                                        {{ strlen($item['color']) ? ', ' . $item['color'] : '' }}--}}
        {{--                                        {{ strlen($item['size']) ? ', ' . $item['size'] : '' }}--}}
        {{--                                    </option>--}}

        {{--                                @endforeach--}}
        {{--                            </select>--}}
        {{--                            --}}{{-- else, finally, display default 'none found' advisory --}}
        {{--                        @else--}}
        {{--                            <div>No available inventory.</div>--}}
        {{--                        @endif--}}

        {{--                    </div>--}}
        {{--                @empty--}}
        {{--                    --}}{{-- do nothing --}}
        {{--                @endforelse--}}

        {{--                <x-buttons.fauxSubmit value="assign assets" wireClick="assignAssets"/>--}}

        {{--            </div>--}}
        {{--        @endif--}}
    </div>
</div>

