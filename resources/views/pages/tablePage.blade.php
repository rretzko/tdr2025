<x-layouts.pages00>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ \Diglactic\Breadcrumbs\Breadcrumbs::render( $dto['header'], $id ?? '') }}
        </h2>
    </x-slot>

    <div class="py-0.5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 py-2 mb-4 overflow-hidden shadow-sm sm:rounded-lg">

                {{-- OBJECTS TABLE --}}
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
                            <x-buttons.addNew route="{{ $dto['addNewButtonRoute'] }}"/>
                        </div>
                        <table class="px-4 shadow-lg w-full">
                            <thead>
                            <tr>
                                @foreach($dto['columnHeaders'] AS $columnHeader)
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
                            @forelse($dto['rows'] AS $row)
                                <tr class=" odd:bg-green-100 ">
                                    @for($i=0; $i<count($dto['columnHeaders']); $i++)
                                        <td class="border border-gray-200 px-1 {{ $row[$i][1] }}">
                                            {!! $row[$i][0]  !!}
                                        </td>
                                    @endfor
                                    <td class="text-center border border-gray-200">
                                        <x-buttons.edit/>
                                    </td>
                                    <td class="text-center border border-gray-200">
                                        <x-buttons.remove/>
                                    </td>
                                </tr>

                            @empty
                                <td colspan="{{ count($dto['columnHeaders']) }}"
                                    class="border border-gray-600 text-center">
                                    No {{ $dto['header'] }} found.
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-layouts.pages00>
