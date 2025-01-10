<div>
    {{-- ROOM SELECTORS --}}
    <div class="flex flex-col sm:flex-row sm:space-x-8 ">
        @foreach($rooms AS $room)
            <div class="flex flex-row space-x-2 items-center ml-4">
                <input type="radio" wire:model.live="roomId" value="{{ $room->id }}">
                <label for="room_{{ $room->id }}">
                    {{ $room->room_name }} ({{ $room->tolerance }})
                </label>
            </div>
        @endforeach

    </div>

    {{-- JUDGE SUMMARY TABLE --}}
    @if($roomId)
        <div class="bg-gray-100 border border-gray-300 rounded-lg p-2">
            @include('components.forms.partials.adjudications.judgeSummaryTable')
        </div>
    @endif

</div>
