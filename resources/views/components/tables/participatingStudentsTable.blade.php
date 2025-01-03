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
            <th class="sr-only">Edit</th>

        </tr>
        </thead>
        <tbody>
        @forelse($rows AS $key => $row)
            <tr class=" odd:bg-green-50 ">

                {{-- COUNTER --}}
                <td class="text-center">
                    {{ $loop->iteration + (($rows->currentPage() - 1) * $recordsPerPage) }}
                </td>

                {{-- SCHOOL --}}
                <td
                    @class(
                        [
                            "border border-gray-200 px-1",
                            'text-gray-400' => ($key && ($rows[$key - 1]->schoolName === $row->schoolName)),
                        ])
                >
                    {{ $row->schoolName }}
                </td>

                {{-- TEACHER --}}
                <td
                    @class([
                          "border border-gray-200 px-1",
                          'text-gray-400' => ($key && ($rows[$key - 1]->teacherFullName === $row->teacherFullName)),
                    ])
                >
                    <div>
                        {{ $row->last_name . ($row->suffix_name ? ' ' . $row->suffix_name : '') . ', ' . $row->first_name . ' ' . $row->middle_name . ($row->prefix_name ? ' (' . $row->prefix_name . ')' : '') }}
                    </div>
                    <div @class([
                          "ml-2 text-sm",
                          'hidden' => ($key && ($rows[$key - 1]->teacherFullName === $row->teacherFullName)),
                        ])
                    >
                        {{ $row->email }}
                    </div>
                    <div @class([
                          "ml-2 text-sm",
                          'hidden' => ($key && ($rows[$key - 1]->teacherFullName === $row->teacherFullName)),
                        ])
                    >
                        {{ $row->phoneMobile }} (c)
                    </div>
                    <div @class([
                          "ml-2 text-sm",
                          'hidden' => ($key && ($rows[$key - 1]->teacherFullName === $row->teacherFullName)),
                        ])
                    >
                        {{ $row->phoneWork }} (w)
                    </div>
                </td>

                {{-- REGISTRANT --}}
                <td class="border border-gray-200 px-1 ">
                    {{ $row->studentLastName . ($row->studentSuffix ? ' ' . $row->studentSuffix : '') . ', ' . $row->studentFirstName . ' ' . $row->studentMiddleName  }}
                </td>

                {{-- GRADE --}}
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row->grade  }}
                    <span class="text-xs italic"> ({{ $row->class_of }})</span>
                </td>

                {{-- Voice Part --}}
                <td class="border border-gray-200 px-1 ">
                    {{ $row->voicePartDescr }}
                </td>

                {{-- Edit button --}}
                <td class="border border-gray-200 px-1 ">
                    <button type="button"
                            wire:click="clickEdit({{ $row->id }})"
                            class="bg-indigo-600 text-white text-xs px-2 rounded-full hover:bg-indigo-700"
                    >
                        Edit
                    </button>
                </td>
            </tr>

        @empty
            <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                No {{ $header }} found.
            </td>
        @endforelse
        </tbody>
    </table>

    {{-- LOADING COMPONENT AND SPINNER --}}
    <x-tables.loadingComponentAndSpinner/>
</div>
