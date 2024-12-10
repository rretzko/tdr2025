<div class="px-4">
    <h2>Student Dossier</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="findStudent" class="border border-t-gray-200 my-2 p-4 rounded-lg shadow-lg">
        <h2 class="font-semibold">Search for a student</h2>
        <fieldset class="flex flex-row space-x-4">
            <div class="flex flex-col">
                <label>
                    <div>First or Last Name</div>
                    <input type="text" wire:model.live.blur="searchFor" class="w-full" autofocus/>
                </label>
            </div>

            <div class="flex flex-col">
                <label>
                    <div class="text-white">Search button</div>
                    <button type="button" class="px-2 mt-2 bg-black text-white rounded-full items-center">
                        Search
                    </button>
                </label>
            </div>

        </fieldset>

        <div id="results" class="mt-2">
            @if(strlen($searchFor) )
                <div class="flex flex-wrap space-y-1 ">
                    @forelse($students AS $studentButton)
                        <button
                            wire:click="clickStudentNameButton({{ $studentButton['id'] }})"
                            @class([
                                "border border-gray-600 rounded-full px-2 mr-1",
                                "bg-gray-200" => (! $studentButton['unassigned']),
                                "bg-red-200" => $studentButton['unassigned'],
                                ])
                        >
                            {{ $studentButton['name'] }}
                        </button>
                    @empty
                        No students found with name like '{{ $searchFor }}'.
                    @endforelse
                </div>
            @endif
        </div>
    </div>{{-- end of findStudent --}}

    @if($student)
        <div id="dossier" class="flex flex-wrap border border-gray-200 my-2 p-4 rounded-lg shadow-lg">

            <div id="bio" class="border border-gray-400 rounded-lg mr-2 p-2">
                <h3 class="bg-gray-200 text-sm text-center font-semibold">bio</h3>
                <div>{{ $student->user->name }}</div>
                <hr/>
                <div class="ml-2">{{$student->user->pronounDescr}}</div>
                <hr/>
                <div class="ml-2">{{ $student->user->email }}</div>
                <hr/>
                <div class="ml-2">{{ $student->phoneMobile }} (c)</div>
                <div class="ml-2">{{ $student->phoneHome }} (h)</div>
                <hr/>
                @if($student->address)
                    <div>{{ $student->address->addressString }}</div>
                @else
                    <div>No home address found.</div>
                @endif
                <hr/>
                <div>Profile created on: {{ $profileCreationDateTime }}</div>
            </div>

            <div id="school" class="border border-gray-400 rounded-lg mr-2 p-2">
                <h3 class="bg-gray-200 text-sm text-center font-semibold">schools</h3>
                @forelse($student->schools AS $schoolKey => $school)
                    @if($schoolKey)
                        <hr/>
                    @endif
                    <div>{{ $school->name }}</div>
                    @forelse($school->activeTeachers AS $teacher)
                        <div class="ml-2">{{ $teacher->user->name }}</div>
                    @empty
                        <div>No teachers found at {{ $school->name }}.</div>
                    @endforelse
                @empty
                    <div>No schools found.</div>
                @endforelse
            </div>

            <div id="emergencyContacts" class="border border-gray-400 rounded-lg mr-2 p-2">
                <h3 class="bg-gray-200 text-sm text-center font-semibold">emergency contacts</h3>
                @forelse($student->emergencyContacts AS $ecKey => $ec)
                    @if($ecKey)
                        <hr/>
                    @endif
                    <div>{{ $ec->name }} ({{ $ec->relationship }})</div>
                    <div class="ml-2">{{ $ec->email }}</div>
                    <div class="ml-2">{{ $ec->phone_mobile }} (c)</div>
                    <div class="ml-2">{{ $ec->phone_work }} (w)</div>
                    <div class="ml-2">{{ $ec->phone_home }} (h)</div>
                @empty
                    <div>No emergency contacts found.</div>
                @endforelse
            </div>

        </div>

        @if($unassigned)
            <div id="addThisStudent" class=" mr-2 p-2">
                <button
                    wire:click="clickAssignStudent()"
                    class="bg-gray-700 text-white border border-gray-600 rounded-full px-2 mr-1 "
                >
                    Add {{ $student->user->name }} To My Roster
                </button>
            </div>
        @endif

    @endif
</div>


