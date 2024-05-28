<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <div class="flex flex-row justify-between px-4 w-full ">
        <div class="w-1/2">
            <input class="w-3/4" type="text" placeholder="Search"/>
        </div>
        <div class="flex justify-end w-1/2">
            <div>
                Filters
            </div>
        </div>
    </div>

    <div class="w-11/12">
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            <x-buttons.addNew/>
        </div>
        <table class="px-4 shadow-lg w-full">
            <thead>
            <tr>
                @foreach($columnHeaders AS $columnHeader)
                    <th class="border border-gray-200 px-1">
                        {{ $columnHeader }}
                    </th>
                @endforeach
                <th class="border border-transparent px-1 sr-only">
                    edit
                </th>
                <th class="border border-gray-200 px-1 sr-only">
                    remove
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse($rows AS $row)
                <tr class=" odd:bg-green-100 ">
                    <td class="border border-gray-200 px-1">
                        {{ $row[1] }} {{-- name --}}
                    </td>
                    <td class="border border-gray-200 px-1">
                        {{ $row[2] }} {{-- address --}}
                    </td>
                    <td class="border border-gray-200 px-1">
                        {{ $row[3] }} {{-- grades taught --}}
                    </td>
                    <td wire:click="toggleActive({{ $row[0] }})"
                        class="border border-gray-200 px-1 flex justify-center items-center">
                        {!! $row[4] !!} {{-- active? --}}
                    </td>
                    <td class="border border-gray-200 px-1">
                        {{ $row[5] }} {{-- work email --}}
                    </td>
                    <td class="border border-gray-200 px-1 flex justify-center items-center">
                        {!! $row[6] !!} {{-- email verified at --}}
                    </td>
                    <td class="border border-gray-200 px-1">
                        {{ $row[7] }} {{-- grades i teach--}}
                    </td>

                    <td class="text-center border border-gray-200">
                        <x-buttons.edit/>
                    </td>
                    <td class="text-center border border-gray-200">
                        <x-buttons.remove/>
                    </td>
                </tr>

            @empty
                <td colspan="{{ count($columnHeaders) }}" class="border border-gray-600 text-center">
                    No {{ $dto['header'] }} found.
                </td>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
