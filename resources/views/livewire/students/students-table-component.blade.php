<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH --}}
    @if($hasSearch)
        <div class="px-4 w-11/12">
            <input class="w-3/4" type="text" placeholder="Search"/>
        </div>
    @endif

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW BUTTON --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            <x-buttons.addNew route="student.create"/>
        </div>

        {{-- FILTERS and TABLE --}}
        <div class="flex flex-row">

            {{-- FILTERS --}}
            @if($hasFilters)
                <div class="flex justify-center">
                    <x-sidebars.filters :filters="$filters" :methods="['schools']"/>
                </div>
            @endif

            {{-- TABLE --}}
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
                <tr class=" odd:bg-green-100 ">
                    <td class="border border-gray-200 px-1">
                        <div>{{ $row['name'] }}</div> {{-- student name --}}
                        <div class="ml-2 text-xs italic">{{ $row['schoolName'] }}</div>
                        <div class="ml-2 text-xs italic">{{ $row['email'] }}</div>
                        <div class="ml-2 text-xs italic">{{ $row['phoneMobile'] }}</div>
                        <div class="ml-2 text-xs italic">{{ $row['phoneHome'] }}</div>
                    </td>
                    <td class="border border-gray-200 px-1 text-center">
                        {{ $row['classOf'] }} {{-- ex. 2026 (11th grade) --}}
                    </td>
                    <td class="border border-gray-200 px-1 text-center">
                        {{ $row['voicePart'] }} {{-- ex. baritone --}}
                    </td>
                    <td class="border border-gray-200 px-1 text-center">
                        {{ $row['height'] }} {{-- ex. 64 (5' 4") --}}
                    </td>
                    <td class="border border-gray-200 px-1 text-center">
                        {{ $row['birthday'] }} {{-- ex. Jan 1, 2010 --}}
                    </td>
                    <td class="border border-gray-200 px-1 text-center">
                        {{ $row['shirtSize'] }}
                    </td>
                    <td class="text-center border border-gray-200">
                        <x-buttons.edit id="{{ $row['studentId'] }}" route="student.edit"/>
                    </td>
                    <td class="text-center border border-gray-200">
                        <x-buttons.remove id="{{ $row['studentId'] }}" livewire="1"/>
                    </td>
                </tr>

            @empty
                <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                    No {{ $dto['header'] }} found.
                </td>
            @endforelse
            </tbody>
            </table>

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>

</div>

