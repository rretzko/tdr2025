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
                        @if(in_array($card['label'], ['ensembles', 'libraries']) && (! auth()->user()->isFounder()))
                            {{-- suppress display --}}
                        @else
                            <x-cards.dashboardCard
                                color="{{ $card['color'] }}"
                                descr="{!! $card['description'] !!}"
                                heroicon="{{ $card['heroicon'] }}"
                                href="{{ $card['href'] }}"
                                label="{{ $card['label'] }}"
                            />
                        @endif
                    @empty
                        <div>None Found.</div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>

</x-layouts.pages00>
