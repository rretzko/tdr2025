<div id="domains" class="bg-white p-2 mr-0.5 rounded-lg space-y-2 h-full">
    <div class="text-indigo-600">
        <a href="{{ route('schools') }}" title="Schools">
            <x-heroicons.building/>
        </a>
    </div>
    <div class="text-green-600">
        <a href="{{ route('students') }}" title="Students">
            <x-heroicons.mortarBoard/>
        </a>
    </div>
    <div class="text-blue-600">
        <a href="{{ route('ensembles') }}" title="Ensembles">
            <x-heroicons.userGroup/>
        </a>
    </div>
    <div class="text-yellow-600">
        <a href="{{ route('libraries') }}" title="Libraries">
            <x-heroicons.bookOpen/>
        </a>
    </div>
    <div class="text-red-600">
        <a href="{{ route('events') }}" title="Events">
            <x-heroicons.calendar/>
        </a>
    </div>
</div>
