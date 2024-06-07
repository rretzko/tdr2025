<x-layouts.pages00>

    <x-slot name="header">
        @if($dto['schoolCount'] > 0)
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ \Diglactic\Breadcrumbs\Breadcrumbs::render( $dto['header'], $id) }}
            </h2>
        @endif
    </x-slot>

    <div class="py-0.5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 py-2 mb-4 overflow-hidden shadow-sm sm:rounded-lg">

                {{-- LIVEWIRE COMPONENT --}}
                @livewire($dto['livewireComponent'], ['dto' => $dto])
            </div>
        </div>
    </div>

</x-layouts.pages00>
