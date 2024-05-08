<x-layouts.pages00>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-0.5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 py-2 mb-4 overflow-hidden shadow-sm sm:rounded-lg">
                {{-- DASHBOARD CARDS --}}
                <div class="flex flex-col justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

                    {{-- SCHOOLS --}}
                    <x-cards.dashboardCard
                        heroicon="building"
                        label="schools"
                        descr="<p>Add/Edit your schools including <b>ensembles</b>, <b>libraries</b>, and
                        even your private studio if you have one!</p>
                        <p>You can also grant/remove co-teacher access to your students.</p>"
                    />

                    {{-- STUDENTS --}}
                    <div class="card w-3/4 md:w-1/4 md:min-h-64 border border-gray-300 p-2 rounded-lg">
                        <a href="" class="space-y-2">
                            <div class="text-green-600 mb-4">
                                <x-heroicons.mortarBoard/>
                            </div>
                            <h2 class="text-blue-500 font-semibold">Students</h2>
                            <div class="text-xs">
                                <p>
                                    Add and edit your students' records.
                                </p>
                            </div>
                        </a>
                    </div>

                    {{-- EVENTS --}}
                    <div class="card w-3/4 md:w-1/4 md:min-h-64 border border-gray-300 p-2 rounded-lg">
                        <a href="" class="space-y-2">
                            <div class="text-red-600 mb-4">
                                <x-heroicons.calendar/>
                            </div>
                            <h2 class="text-blue-500 font-semibold">Events</h2>
                            <div class="text-xs">
                                <p>
                                    Update your student registration information for an upcoming auditioned event
                                    (ex. Region, All-State, etc.).
                                </p>
                                <p>
                                    Open adjudication pages (when available).
                                </p>
                                <p>
                                    Even create and manage an event of your own!
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.pages00>
