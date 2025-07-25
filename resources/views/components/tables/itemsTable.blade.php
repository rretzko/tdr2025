<div class="relative overflow-x-auto">

    {{-- Success Message --}}
    @if(session()->has('successMessage'))
        <div class="bg-green-100 text-sm text-green-800 mb-2 px-2 rounded-lg w-fit">
            {{ session('successMessage') }}
        </div>
    @endif

    {{-- DISPLAY PULL SHEET BUTTON --}}
    @if($dto['header'] === 'ensemble library')
        <div class="text-right">
            <button wire:click="downloadPullSheetPdf({{ $library->id }})"
                    class="text-sm text-blue-500"
            >
                Pull Sheet (<span id="itemsToPullCount"></span>)
            </button>
        </div>
    @endif

    <table class="text-sm px-4 shadow-lg w-full">
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
                @if($dto['header'] != 'ensemble library')
                    <th colspan="2" class="border border-transparent px-1">
                        <button wire:click="downloadPullSheetPdf({{ $library->id }})"
                                class="text-sm text-blue-500"
                        >
                            Pull Sheet (<span id="itemsToPullCount"></span>)
                        </button>
                    </th>
                @endif
        </tr>
        </thead>
        <tbody>

        @forelse($rows AS $row)

            <tr class="odd:bg-green-50 hover:bg-green-100">
                <td class="border border-gray-200 px-1 text-center">
                    {{ $loop->iteration }}
                </td>
                <td class="border border-gray-200 px-1 text-left w-24">
                    <div>{{ $row['item_type'] }}</div>
                    <div class="ml-2 text-xs italic">
                        {{ $locations[$row['libItemId']] }}
                    </div>
                </td>
                {{-- title --}}
                <td class="border border-gray-200 px-1 text-left">
                    <div>{{ $row['alpha'] }}</div>
                    @if(array_key_exists($row['libItemId'], $medleySelections))
                        <div class="ml-4 text-xs italic">
                            @foreach($medleySelections[$row['libItemId']] AS $libMedleySelection)
                                <div>{{ $libMedleySelection->title }}</div>
                            @endforeach
                        </div>
                    @endif
                </td>
                {{-- count --}}
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['count'] }}
                </td>
                {{-- artists --}}
                <td class="border border-gray-200 px-1 text-left ">
                    @if($row['composerName'])
                        <div class="font-bold">{{ $row['composerName'] }}</div>
                    @endif
                    @if($row['arrangerName'])
                        <div>{{ $row['arrangerName'] }} <span class="text-xs">(arr)</span></div>
                    @endif
                        @if($row['wamName'])
                            <div>{{ $row['wamName'] }} <span class="text-xs">(wam)</span></div>
                        @endif
                    @if($row['wordsName'])
                        <div>{{ $row['wordsName'] }} <span class="text-xs">(words)</span></div>
                    @endif
                        @if($row['musicName'])
                            <div>{{ $row['musicName'] }} <span class="text-xs">(music)</span></div>
                        @endif
                        @if($row['choreographerName'])
                            <div>{{ $row['choreographerName'] }} <span class="text-xs">(choreo)</span></div>
                        @endif
                </td>

                {{-- voicing --}}
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['voicingDescr'] }}
                </td>

                {{-- tags --}}
                <td class="border border-gray-200 px-1 text-left text-sm cursor-help">
                    {{ implode(', ', $tags[$row['libItemId']]) }}
                </td>

                {{-- student librarians do not have access to programs module --}}
                @if(auth()->user()->isTeacher())
                    <td class="border border-gray-200 px-1 text-center text-sm cursor-help w-20">
                        @forelse($performances[$row['libItemId']] AS $programId => $performanceDate)
                            <a
                                href="{{ route('programs.show',['program' => $programId]) }}"
                                class="text-blue-500"
                                title="open program"
                            >
                                {{ $performanceDate }}
                            </a>
                        @empty
                            none
                        @endforelse
                    </td>
                @endif

                {{-- pull --}}
                <td class="relative border border-gray-300">
                    <div class="flex justify-center items-center h-full">
                        <input type="checkbox"
                               wire:model="itemsToPull"
                               class="item-checkbox"
                               value="{{  $row['libItemId'] }}"
                        />
                    </div>
                </td>

                {{-- edit --}}
                @if($dto['header'] != 'ensemble library')
                    <td class="text-center border border-gray-200 px-1">
                        <x-buttons.edit id="{{ $row['libItemId'] }}" :livewire="true" id="{{ $row['libItemId'] }}"/>
                    </td>

                    {{-- remove --}}
                    {{-- ONLY TEACHER MAY REMOVE ITEMS --}}
                    @if(auth()->user()->isTeacher())
                        <td class="text-center border border-gray-200 px-1">
                            <x-buttons.remove id="{{ $row['libItemId'] }}" livewire="1"
                                              message="Are you sure you want to remove this library item?"/>
                        </td>
                    @endif
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                    No {{ $header }} found.
                </td>
            </tr>
        @endforelse
        </tbody>

    </table>

    {{-- LOADING COMPONENT AND SPINNER --}}
    <x-tables.loadingComponentAndSpinner/>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const countDisplay = document.getElementById('itemsToPullCount');
            const checkboxes = document.querySelectorAll('input.item-checkbox');

            function updateCount() {
                // Count how many checkboxes are checked
                const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
                countDisplay.textContent = checkedCount;
            }

            // Attach change event listeners to all checkboxes
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateCount);
            });

            // Initialize count on page load
            updateCount();
        });
    </script>

</div>
