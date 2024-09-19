@if(count($dto['rooms']) === 1)
    {{-- GET SINGLE ROOM DATA --}}
    @php $room = array_shift($dto['rooms']); @endphp
    @include('pdfs.adjudications.backupPapers.events.25.singleRoom')
@else
    @include('pdfs.adjudications.backupPapers.events.25.allRooms')
@endif
