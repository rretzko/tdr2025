@props([
    'recordsPerPage',
    'rows'
])
<div>
    @if(count($rows) <= $recordsPerPage)
        <div>Count: {{ count($rows) }}</div>
    @endif
    {{-- LINKS:TOP --}}
    <div class="shadow-lg">
        {{ $rows->links() }}
    </div>
</div>
