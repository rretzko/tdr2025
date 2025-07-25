<x-layouts.pages00>

    {{-- HEADER --}}
    <x-slot name="header">
        @if($dto['schoolCount'] > 0)
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ \Diglactic\Breadcrumbs\Breadcrumbs::render( $dto['header'], $id ?? '' ) }}
            </h2>
        @endif
    </x-slot>

    {{-- SIDEBAR --}}
    <div class="py-0.5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row">

            {{-- DOMAINS for non-student-librarians--}}
            @if(! auth()->user()->isLibrarian())
                <x-sidebars.domains/>
            @endif

            <div class="bg-white dark:bg-gray-800 py-2 mb-4 overflow-hidden shadow-sm sm:rounded-lg w-full">

                {{-- LIVEWIRE COMPONENT --}}
                @livewire($dto['livewireComponent'], ['dto' => $dto])
            </div>
        </div>

    </div>

</x-layouts.pages00>
