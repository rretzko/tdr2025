<div class="flex flex-col">
    {{-- STUDENT COUNT --}}
    <div class="">{{ $progress['total']['count'] }} students</div>

    {{-- PROGRESS BAR --}}
    <div class="flex flex-row w-full border border-gray-600">
        @foreach($progress AS $label => $data)
            @if(($label !== 'total') && ($data['count'] > 0))
                <div class="flex justify-center {{ $barFormats[$label] }} " style="width: {{ $data['wpct'] }}">
                    {{ $data['count'] }}
                </div>
            @endif
        @endforeach
    </div>
</div>
