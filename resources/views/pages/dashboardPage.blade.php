<x-layouts.pages00>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ \Diglactic\Breadcrumbs\Breadcrumbs::render( $dto['header'], $id ?? '') }}
        </h2>
    </x-slot>

    <div class="py-0.5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 py-2 mb-4 overflow-hidden shadow-sm sm:rounded-lg">

                @if($dto['dashboardHeader'])
                    <div class="font-semibold ml-20 my-2">
                        {{ $dto['dashboardHeader'] }}
                    </div>
                @endif

                {{-- DASHBOARD CARDS --}}
                <div class="flex flex-col justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

                    @forelse($dto['cards'] AS $card)
                        {{-- temporarily suppress display of ensemble and library cards except for founder --}}
                        @if(in_array($card['label'], ['ensembles', 'libraries']) && (! auth()->user()->isFounder()))
                            {{-- suppress display --}}
                        @else

                            {{-- DETERMINE IF CARDS CONTAIN ROLE-BASED IDENTIFIERS --}}
                            @if($card['role'])
                                @php

                                    if(!isset($role)){  //print header row
                                        //initialize var $role if not set
                                        $role = $card['role'];
                                        //close preceding div
                                        echo "</div>";
                                        //open header for role identification
                                        echo "<header class='bg-gray-200 pl-2 mr-4 font-semibold ml-20 border border-white border-t-gray-200'>" . ucwords($card['role']) . '</header>';
                                        //re-open preceding div for formatting
                                        echo "
                                        <div class='flex flex-col justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2'>";
                                    }
                                @endphp
                            @endif

                            @if(isset($role) && ($role !== $card['role']))
                                @php
                                    //close preceding div
                                    echo "</div>";
                                    //open header for role identification
                                    echo "<header class='bg-gray-200 pl-2 mr-4 font-semibold ml-20 mt-4 pt-2 border border-white border-t-gray-200'>" . ucwords($card['role']) . '</header>';
                                    //re-open preceding div for formatting
                                    echo "
                                    <div class='flex flex-col justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2'>";
                                @endphp
                            @endif

                            <x-cards.dashboardCard
                                color="{{ $card['color'] }}"
                                descr="{!! $card['description'] !!}"
                                heroicon="{{ $card['heroicon'] }}"
                                href="{{ $card['href'] }}"
                                label="{{ $card['label'] }}"
                            />

                            @if($card['role'])
                                @php
                                    if(isset($role) && ($role !== $card['role'])){
                                        $role = $card['role'];
                                    }
                                @endphp
                            @endif

                        @endif
                    @empty
                        <div>None Found.</div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>

</x-layouts.pages00>
