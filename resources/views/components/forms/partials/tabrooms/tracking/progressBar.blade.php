<div class="flex flex-col">
    {{-- STUDENT COUNT --}}
    <div class="">{{ $progress['total']['count'] }} students</div>

    {{-- PROGRESS BAR --}}
    <div class="flex flex-row w-full border border-gray-600">
        @foreach($progress AS $label => $data)
            @if(($label !== 'total') && ($data['count'] > 0))
                <div style="width: {{ $data['wpct'] }};"
                    @class([
                    'flex justify-center',
                    'bg-black text-white' => ($label === 'pending'),
                    'bg-yellow-400 text-black' => ($label === 'wip'),
                    'bg-green-500 text-white' => ($label === 'completed'),
                    'bg-red-500 text-yellow-400' => ($label === 'errors'),
                    ])
                >
                    {{ $data['count'] }}
                </div>
            @endif
        @endforeach
    </div>
</div>
