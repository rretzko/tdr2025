<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    @if($hasFilters || $hasSearch)
        <div class="flex flex-row justify-between px-4 w-full ">
            @if($hasSearch)
                <div class="w-1/2">
                    <input class="w-3/4" type="text" placeholder="Search"/>
                </div>
            @endif

            @if($hasFilters)
                <div class="flex justify-end w-1/2">
                    <div>
                        Filters
                    </div>
                </div>
            @endif
        </div>
    @endif

    <div class="w-11/12">
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            <x-buttons.addNew route="school.create"/>
        </div>
        <table class="px-4 shadow-lg w-full">
            <thead>
            <tr>
                @foreach($columnHeaders AS $columnHeader)
                    <th
                        class="border border-gray-200 px-1 @if($columnHeader === 'active?') text-blue-500 @endif"
                        title="@if($columnHeader === 'active?') Click to change... @endif"
                    >
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
                <tr class=" odd:bg-green-50 ">
                    <td class="border border-gray-200 px-1">
                        {{ $row->schoolName }} {{-- name --}}
                    </td>
                    <td class="border border-gray-200 px-1">
                        {{ $row->city }} in {{ $row->countyName }}, {{ $row->postalCode }} {{-- address --}}
                    </td>
                    <td class="border border-gray-200 px-1">
                        {{ $row->gradesTaught }} {{-- grades taught --}}
                    </td>
                    <td wire:click="toggleActive({{ $row->schoolId }})"
                        @class([
        'border border-gray-200 border-b-transparent border-t-transparent px-1 flex justify-center items-center cursor-pointer mt-4 xl:mt-0',
        'text-green-600' => $row->active,
        'text-red-500' => ! $row->active,
        ])
                    >
                        {!! $row->active ? '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
     class="w-6 h-6">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
</svg>'
: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
    <path stroke-linecap="round" stroke-linejoin="round" d="M7.498 15.25H4.372c-1.026 0-1.945-.694-2.054-1.715a12.137 12.137 0 0 1-.068-1.285c0-2.848.992-5.464 2.649-7.521C5.287 4.247 5.886 4 6.504 4h4.016a4.5 4.5 0 0 1 1.423.23l3.114 1.04a4.5 4.5 0 0 0 1.423.23h1.294M7.498 15.25c.618 0 .991.724.725 1.282A7.471 7.471 0 0 0 7.5 19.75 2.25 2.25 0 0 0 9.75 22a.75.75 0 0 0 .75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 0 0 2.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384m-10.253 1.5H9.7m8.075-9.75c.01.05.027.1.05.148.593 1.2.925 2.55.925 3.977 0 1.487-.36 2.89-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398-.306.774-1.086 1.227-1.918 1.227h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 0 0 .303-.54" />
</svg>' !!} {{-- active? --}}
                    </td>
                    <td class="border border-gray-200 px-1">
                        {{ $row->email }} {{-- work email --}}
                    </td>
                    <td @class([
        'border border-gray-200 border-b-transparent border-t-transparent px-1 flex justify-center items-center',
        'text-green-600' => $row->email_verified_at,
        'text-red-500' => ! $row->email_verified_at,
        ])
                    >
                        {!! $row->email_verified_at
? '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
     class="w-6 h-6">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
</svg>'
: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
    <path stroke-linecap="round" stroke-linejoin="round" d="M7.498 15.25H4.372c-1.026 0-1.945-.694-2.054-1.715a12.137 12.137 0 0 1-.068-1.285c0-2.848.992-5.464 2.649-7.521C5.287 4.247 5.886 4 6.504 4h4.016a4.5 4.5 0 0 1 1.423.23l3.114 1.04a4.5 4.5 0 0 0 1.423.23h1.294M7.498 15.25c.618 0 .991.724.725 1.282A7.471 7.471 0 0 0 7.5 19.75 2.25 2.25 0 0 0 9.75 22a.75.75 0 0 0 .75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 0 0 2.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384m-10.253 1.5H9.7m8.075-9.75c.01.05.027.1.05.148.593 1.2.925 2.55.925 3.977 0 1.487-.36 2.89-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398-.306.774-1.086 1.227-1.918 1.227h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 0 0 .303-.54" />
</svg>'!!} {{-- email verified at --}}
                    </td>
                    <td class="border border-gray-200 px-1">
                        {{ $row->gradesITeach }} {{-- grades i teach--}}
                    </td>
                    <td class="border border-gray-200 px-2">
                        @if($row->subjects)
                            @forelse($row->subjects AS $subject)
                                <div>{{ $subject }}</div>
                            @empty
                                <div>None</div>
                            @endforelse
                        @else
                            <div>None</div>
                        @endif
                    </td>

                    <td class="text-center border border-gray-200">
                        <x-buttons.edit id="{{ $row->schoolId }}" livewire="1"/>
                    </td>

                    <td class="text-center border border-gray-200">
                        @if($schoolCount > 1)
                            <x-buttons.remove id="{{ $row->schoolId }}" livewire="1"
                                              message="Are you sure you want to remove {{ $row->schoolName }} from your roster?"/>
                        @endif
                    </td>
                </tr>

            @empty
                <td colspan="{{ count($columnHeaders) }}" class="border border-gray-600 text-center">
                    No {{ $dto['header'] }} found.
                </td>
            @endforelse
            </tbody>
        </table>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>

</div>
