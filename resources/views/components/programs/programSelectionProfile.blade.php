@props([
    'artistBlock',
    'title',
    'voicing',
])
<div class="border border-gray-100 border-t-gray-300 border-b-gray-300 my-4">
    <div class="text-sm mb-2 italic">
        Library item details may be edited in the <a href="\libraries" class="text-blue-500">Libraries</a> module.
    </div>
    <div>{!! $title !!} ({{ $voicing }})</div>
    <div class="ml-2 text-sm">
        {!! $artistBlock !!}
    </div>
</div>
