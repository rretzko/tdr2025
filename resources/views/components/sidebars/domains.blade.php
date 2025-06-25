<div id="domains"
     class="bg-white p-2 mr-0.5 mb-0.5 flex flex-row md:flex-col space-x-8 md:space-x-0 md:space-y-2 rounded-lg h-full ">
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
    @if( auth()->user()->hasLibrary())
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
        <div class="text-black">
            <a href="{{ route('programs') }}" title="Programs">
                <x-heroicons.ticket/>
            </a>
        </div>

    @endif
    <div class="text-red-600">
        <a href="{{ route('events.dashboard') }}" title="Events">
            <x-heroicons.calendar/>
        </a>
    </div>
</div>
