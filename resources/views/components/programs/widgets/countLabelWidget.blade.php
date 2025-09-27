@props(
    [
        'data' => []
]
)
<div class="">

    @foreach($data AS $set)

        <div class="flex flex-row">
            <div class="w-12 text-right pr-2 ">
                {{ $set['count'] }}
            </div>
            <div>
                {{ $set['label'] }}
            </div>
        </div>

    @endforeach
</div>
