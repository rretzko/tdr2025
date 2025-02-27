<div class="border border-gray-200 rounded-lg shadow-lg p-2 my-2 mx-auto w-11/12">
    {{-- PARTICIPANT --}}
    <div class="space-y-0.5 mb-2">
        {{-- HEADER --}}
        <label class="font-semibold">@if($form->sysId)
                Update
            @else
                Add
            @endif Co-registration Manager</label>

        {{-- PARTICIPANT NAME --}}
        <div class="flex flex-row items-center">
            <label style="width: 4rem;">Name</label>
            <select wire:model.live="form.versionParticipantId">
                @if($form->sysId === 0)
                    <option value="0">- select -</option>
                @endif
                @forelse($participants AS $participant)
                    <option value="{{ $participant['id'] }}">
                        {{ $participant['alphaName'] }}
                    </option>
                @empty
                    <option value="0">
                        No participants found
                    </option>
                @endforelse
            </select>
            @error('form.userId')
            <div>{{ $errors['userId']->message }}</div>
            @enderror
        </div>

        {{-- COUNTIES --}}
        <div class="flex flex-col w-full">
            <label style="">Assigned to the following counties</label>
            <div>
                @forelse($availableCounties AS $countyId => $county)
                    <div wire:key="countyId_{{ $countyId }}">
                        <input type="checkbox" wire:model.live="form.countyIds" value="{{ $countyId }}"/>
                        <label>{{ $county }}</label>
                    </div>

                @empty
                    <div>No counties found</div>
                @endforelse
            </div>

        </div>
    </div>

    <div class=" space-y-0.5 mb-2">
        <button type="button" class="bg-black text-white px-2 rounded shadow-lg"
                wire:click="@if($form->sysId) updateCoregistrationManager @else saveCoregistrationManager @endif">
            @if($form->sysId)
                Update
            @else
                Add
            @endif Coregistration Manager
        </button>
    </div>

</div>
