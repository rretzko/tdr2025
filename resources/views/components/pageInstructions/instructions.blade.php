@props([
    'firstTimer' => 'true',
    'instructions',
])
<div class="px-1 py-2 mx-2 my-1 bg-gray-100 rounded-lg">

    <div id="hideShowHeader" class="px-2" x-data="{ expanded:{{ $firstTimer }} }">
        <div class="flex flex-row justify-between">
            <div class="font-semibold">Page Instructions</div>
            <button type="button" x-on:click="expanded = ! expanded">
                <span x-show="! expanded" class="text-green-600 font-semibold">Show...</span>
                <span x-show="expanded" class="text-red-600">Hide...</span>
            </button>
        </div>

        <div id="instructions" class="p-8" x-show="expanded" x-transition.enter.duration.500ms
             x-transition:leave.duration.300ms>
            <style>
                p {
                    margin-bottom: 1rem;
                }

                ul {
                    margin-left: 3rem;
                    margin-top: 0;
                    list-style-type: disc;
                }
            </style>
            {!! $instructions !!}
        </div>

    </div>

</div>
