<div class="flex flex-col sm:flex-row border border-transparent border-y-gray-500">

    {{-- CRITERIA --}}
    <div class="flex flex-wrap mt-1 w-1/2">

        {{-- SCHOOL YEAR --}}
        <label class="text-sm flex flex-col items-center">
            <div class="w-24">School Year</div>
            <select wire:model.live="schoolYear" class="text-sm">
                <option value="0">all</option>
                <option value="2026">2026</option>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
            </select>
        </label>

        {{-- VOICING --}}
        <label class="text-sm flex flex-col items-center">
            <div class="w-24">Voicing</div>
            <select wire:model.live="voicing" class="text-sm">
                <option value="all">all</option>
                <option value="mixed">mixed</option>
                <option value="treble">treble</option>
                <option value="ttbb">ttbb</option>
            </select>
        </label>

        {{-- CRITERIA CONTINUED --}}
        <div class="flex flex-col my-2 ml-1 mt-5 pb-2 ">

            {{-- A CAPPELLA --}}
            <label class="text-sm flex flex-row items-center space-x-2">
                <input type="checkbox" wire:model.live="acappella">
                <span>A Cappella</span>
            </label>

            {{-- JAZZ/SHOW/POP --}}
            <label class="text-sm flex flex-row items-center space-x-2">
                <input type="checkbox" wire:model.live="jazz">
                <span>Jazz/Show/Pop</span>
            </label>
        </div>
    </div>

    {{-- CRITERIA-BASED REPORT INFORMATION --}}
    <div class="w-1/2 ">
{{--        <h3 class="font-semibold">Report Information {{ $schoolYear ? ' for ' . $schoolYear : ' for all school years' }}</h3>--}}
        <h3 class="font-semibold">Summary Information for {{ $tableFor }}</h3>

        {{-- MAKE WIDGET WITH SUMMARY DATA --}}
        <x-programs.widgets.countLabelWidget :data="$data" />

    </div>
</div>
