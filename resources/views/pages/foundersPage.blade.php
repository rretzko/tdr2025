<x-layouts.pages00>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ \Diglactic\Breadcrumbs\Breadcrumbs::render( $dto['header']) }}
        </h2>
    </x-slot>

    <div class="py-0.5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 py-2 mb-4 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <h2 class="font-semibold mb-2">Founder's Page</h2>

                <div id="container" class="space-y-1 bg-gray-700 p-2">

                    {{-- LOG IN AS --}}
                    <div class="border border-gray-400 p-2 bg-white">
                        @include('components.forms.partials.logInAsForm')
                    </div>

                    {{-- PAYPAL MANUAL ENTRY --}}
                    <div class="border border-gray-400 p-2 bg-white">
                        @include('components.forms.partials.paypalManualEntryForm')
                    </div>

                    {{-- EPAYMENT UPLOAD --}}
                    <div class="border border-gray-400 p-2 bg-white">
                        @include('components.forms.partials.epayments.ePaymentUploadForm')
                    </div>

                    {{-- TABROOM SCORING TEST --}}
                    <div class="border border-gray-400 p-2 bg-white flex flex-row space-x-2">
                        <a href="{{ route('founder.find-open-versions-without-scores') }}" class="text-blue-500">
                            <button class="bg-green-700 text-white px-2 py-1 rounded ">
                                Create Fake Scores
                            </button>
                        </a>

                        <a href="" class="text-blue-500">
                            <button class="bg-red-700 text-white px-2 py-1 rounded" >
                                Delete Fake Scores
                            </button>
                        </a>
                    </div>

                    {{-- STUDENT DOSSIER --}}
                    <div class="border border-gray-400 p-2 bg-white">
                        <a href="" class="text-blue-500">
                            Open Student Dossier
                        </a>
                    </div>

                </div>{{-- end of container --}}

            </div>
        </div>
    </div>

</x-layouts.pages00>
