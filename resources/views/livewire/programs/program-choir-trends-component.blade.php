<div class="px-4">
    <h2>Programs Choir Trends</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- GLOBAL WIDGET(s) --}}
    <div class="flex flex-wrap justify-center">

        {{-- GLOBAL WIDGET --}}
        <div id="widget01" class="border border-black p-2 rounded-lg shadow-lg w-fit ml-2 mt-4">
            <header class="font-bold">
                Global Program Information
            </header>

            {{-- ADD WIDGET --}}
            <x-programs.widgets.countLabelWidget :data=$widget01 />

        </div>

    </div>

    {{-- REPORT --}}
    <div class="w-full">

        {{-- BY SCHOOL YEAR WIDGET --}}
        <div id="widget01" class="border border-black p-2 rounded-lg shadow-lg ml-2 mt-4">
            <header class="font-bold">
                Report Detail By School Year
            </header>

            @livewire('programs.choir-trends-school-year-widget')


        </div>
    </div>

</div>
