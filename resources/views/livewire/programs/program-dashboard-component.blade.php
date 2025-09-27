<div class="px-4">
    <h2>Programs Dashboard</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div class="flex flex-wrap justify-center">

        {{-- GLOBAL WIDGET --}}
        <div id="widget01" class="border border-black p-2 rounded-lg shadow-lg w-fit ml-2 mt-4">
            <header class="font-bold">
                Global Program Information
            </header>

            <x-programs.widgets.countLabelWidget :data=$widget01 />

        </div>

        {{-- BY SCHOOL YEAR WIDGET --}}
        <div id="widget01" class="border border-black p-2 rounded-lg shadow-lg w-fit ml-2 mt-4">
            <header class="font-bold">
                Program Information By School Year
            </header>

            <div class="text-center italic">
                Coming soon...
            </div>
{{--            <x-programs.widgets.countLabelWidget :data=$widget01 />--}}

        </div>
    </div>

</div>
