<div class="flex flex-row flex-wrap  border border-white border-t-gray-400 border-b-gray-400 my-4">
    @foreach($roomList AS $room)
        <div class="flex flex-row w-1/2 space-x-2 md:w-1/3 lg:w-1/6 items-center">
            <input type="radio" wire:model.live="roomId" value="{{ $room->id }}">
            <label>{{ $room->roomName }}</label>
        </div>
    @endforeach
</div>
