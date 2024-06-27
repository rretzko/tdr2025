@props([
    'recordsPerPage',
    'rows'
])
<div>
    @if($rows->total() < $recordsPerPage)
        <div>Count: {{ count($rows) }}</div>
    @endif
    {{-- LINKS:TOP --}}
    <div class="shadow-lg">
        {{ $rows->links() }}
    </div>
</div>
