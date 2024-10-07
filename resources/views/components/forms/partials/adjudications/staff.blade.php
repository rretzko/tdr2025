<div>

    {{-- HEADER --}}
    <div class="flex flex-row justify-between">
        <label>Room Staff</label>
        <div wire:click="$toggle('showStaff')" class="font-semibold text-green-600 cursor-pointer">
            @if($showStaff)
                Hide...
            @else
                Show...
            @endif
        </div>
    </div>

    {{-- STAFF TABLE --}}
    @if($showStaff)
        <table class="border-collapse text-sm">
            <tbody>
            @forelse($staff AS $judge)
                <tr>
                    <td class="border border-gray-600 px-2">{{ $judge['name'] }}</td>
                    <td class="border border-gray-600 px-2">{{ ucwords($judge['role']) }}</td>
                    <td class="border border-gray-600 px-2">{{ $judge['email'] }}</td>
                    <td class="border border-gray-600 px-2">{{ $judge['mobile'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No staff found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endif
</div>
