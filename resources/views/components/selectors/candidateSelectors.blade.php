<div class="w-1/2 sm:w-1/4 overflow-y-auto h-screen">
    <ul class="list-none ml-1">

        @foreach($rows as $row)
            <li>
                <button wire:click="selectCandidate({{ $row->candidateId }})"
                        class="text-xs text-left block py-1 border-b hover:text-blue-500">
                    <input type="checkbox" class="w-2 h-2 mb-0.5 {{ $row->status }}"/>
                    {{ $row->last_name . ($row->suffix_name ? ' ' . $row->suffix_name : '') . ', ' . trim($row->first_name . ' ' . $row->middle_name) }}
                </button>
            </li>
        @endforeach

    </ul>
</div>
