@props([
    'columnHeaders',
    'header',
    'recordsPerPage',
    'rows',
    'sortAsc',
    'sortColLabel',
])
<div class="relative">

    <table class="px-4 shadow-lg w-full">
        <thead>
        <tr>
            @foreach($columnHeaders AS $columnHeader)
                <th class='border border-gray-200 px-1'>
                    <button
                        @if($columnHeader['sortBy']) wire:click="sortBy('{{ $columnHeader['sortBy'] }}')" @endif
                        @class([
                        'flex items-center justify-center w-full gap-2 ',
                        'text-blue-500' => ($columnHeader['sortBy'])
                        ])
                    >
                        <div>{{ $columnHeader['label'] }}</div>
                        @if($sortColLabel === $columnHeader['sortBy'])
                            @if($sortAsc)
                                <x-heroicons.arrowLongUp/>
                            @else
                                <x-heroicons.arrowLongDown/>
                            @endif
                        @endif
                    </button>
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
                <td class="text-center">
                    {{ $loop->iteration + (($rows->currentPage() - 1) * $recordsPerPage) }}
                </td>
                <td class="border border-gray-200 px-1">
                    <div>{{ $row['name'] }}</div>
                    <div class="ml-2 text-xs italic">{{ $row['short_name'] }}</div>
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['senior_class_of'] }} {{-- ex. 2026 (11th grade) --}}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['status'] }} {{-- ex. inactive --}}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    @if($row['epayment_student'])
                        student
                    @endif
                    @if($row['epayment_teacher'])
                        <br/>teacher
                    @endif
                </td>
                <td class="mx-auto text-center border border-gray-200 border-b-transparent">
                    <div class="flex flex-col space-y-1 md:w-5/6 lg:w-3/4 mx-2 lg:mx-4">

                        <div>
                            @if($row['fee_registration'])
                                {{ '$' . number_format(($row['fee_registration'] / 100), 2) . ' (reg)' }}
                            @endif
                        </div>

                        <div>
                            @if($row['fee_on_site_registration'])
                                {{ '$' . number_format(($row['fee_on_site_registration'] / 100), 2) . ' (on-site)' }}
                            @endif
                        </div>

                        <div>
                            @if($row['fee_participation'])
                                {{ '$' . number_format(($row['fee_participation'] / 100), 2) . ' (part)' }}
                            @endif
                        </div>

                    </div>

                </td>
                <td class="mx-auto text-center border border-gray-200 border-b-transparent">
                    <div class="flex flex-col space-y-1 md:w-5/6 lg:w-3/4 mx-2 lg:mx-4">

                        <div>
                            @if($row['pitch_files_student'])
                                student
                            @endif
                        </div>

                        <div>
                            @if($row['pitch_files_teacher'])
                                teacher
                            @endif
                        </div>

                    </div>

                </td>
                <td class="text-center border border-gray-200">
                    {{--                    @can('update', \App\Models\Events\Versions\Version::find($row['id']))--}}
                    <x-buttons.edit id="{{ $row['id'] }}" route="version.show"/>
                    {{--                    @endcan--}}
                </td>
                <td class="text-center border border-gray-200">
                    {{--                    @can('delete', \App\Models\Events\Versions\Version::find($row['id']))--}}
                    <x-buttons.remove id="{{ $row['id'] }}" livewire="1"/>
                    {{--                    @endcan--}}
                </td>
            </tr>

        @empty
            <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                No {{ $header }} found.
            </td>
        @endforelse
        </tbody>
    </table>


    <div>
        @if(auth()->user()->isFounder())
            <h2 class="font-semibold underline mt-4">To-Dos</h2>
            <ul>
                <li>Event selection by roles</li>
            </ul>
        @endif
    </div>

    {{-- LOADING COMPONENT AND SPINNER --}}
    <x-tables.loadingComponentAndSpinner/>
</div>
