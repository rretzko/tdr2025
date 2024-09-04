<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">

        <div id="tranferFrom" class="bg-red-50 pb-2 px-2 items-center rounded-lg shadow-lg w-full">

            <h3 class="font-semibold text-center">Transfer From</h3>

            <fieldset id="fromVars">

                {{-- SCHOOL FROM --}}
                <div class="flex flex-col mb-2">
                    <label for="schoolIdFrom">School</label>
                    <select wire:model.live.debounce="schoolIdFrom" class="w-11/12" autofocus>
                        <option value="0">- select -</option>
                        @foreach($schools AS $school)
                            <option value="{{ $school['id'] }}" class="text-sm">
                                {{ $school['name'] }} ({{ $school['countyName'] . ' ' . $school['abbr'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TEACHER FROM --}}
                <div class="flex flex-col mb-2">
                    <label for="teacherIdFrom">Teacher</label>
                    <select wire:model.live.debounce="teacherIdFrom" class="w-11/12">
                        <option value="0">- select -</option>
                        @foreach($teacherFroms AS $teacherFrom)
                            <option value="{{ $teacherFrom['id'] }}" class="text-sm">
                                {{ $teacherFrom['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TRANSFER ERROR --}}
                @if(count($transferErrors))
                    @foreach($transferErrors AS $message)
                        <div class="text-red-600">
                            {{ $message }}
                        </div>
                    @endforeach
                @endif

                {{-- STUDENTS FROM --}}
                <div class="flex flex-col mb-2">
                    <label for="studentIdFrom" class="font-semibold underline">
                        Students <span class="text-xs">({{ count($studentFroms) }})</span>
                    </label>
                    @forelse($studentFroms AS $studentFrom)
                        <div class="flex flex-row space-x-2 ml-2 items-center"
                             wire:key="studentFrom-{{ $studentFrom['id'] }}">
                            <input
                                type="checkbox"
                                wire:model.live="studentIdFroms"
                                value="{{ $studentFrom['id'] }}"
                                class="w-3 h-3"
                            />
                            <label for="" class="text-xs">{{ $studentFrom['name'] }} ({{ $studentFrom['class_of'] }}
                                )</label>
                        </div>
                    @empty
                        <div class="text-xs ml-2">
                            No current students found.
                        </div>
                    @endforelse
                </div>

            </fieldset>{{-- end of fromVars --}}

        </div>

        <div id="tranferTo" class="bg-green-50 pb-2 px-2 items-center shadow-lg w-full">

            <h3 class="font-semibold text-center">Transfer To</h3>

            <fieldset id="toVars">

                {{-- SCHOOL TO --}}
                <div class="flex flex-col mb-2">
                    <label for="schoolIdTo">School</label>
                    <select wire:model.live.debounce="schoolIdTo" class="w-11/12">
                        <option value="0">- select -</option>
                        @foreach($schools AS $school)
                            <option value="{{ $school['id'] }}" class="text-sm">
                                {{ $school['name'] }} ({{ $school['countyName'] . ' ' . $school['abbr'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TEACHER TO --}}
                <div class="flex flex-col mb-2">
                    <label for="teacherIdTo">Teacher</label>
                    <select wire:model.live.debounce="teacherIdTo" class="w-11/12">
                        <option value="0">- select -</option>
                        @foreach($teacherTos AS $teacherTo)
                            <option value="{{ $teacherTo['id'] }}" class="text-sm">
                                {{ $teacherTo['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CURRENT STUDENTS --}}
                <div class="flex flex-col mb-2">
                    <label for="studentIdTo" class="font-semibold underline">
                        Current Students <span class="text-xs">({{ count($studentTos) }})</span>
                    </label>
                    @forelse($studentTos AS $studentTo)
                        <div class="flex flex-row space-x-2 ml-2">
                            <label for="" class="ml-2 text-xs">{{ $studentTo['name'] }} ({{ $studentTo['class_of'] }}
                                )</label>
                        </div>
                    @empty
                        <div class="ml-2 text-xs">
                            No current students found.
                        </div>
                    @endforelse
                </div>{{-- end of toVars --}}

                {{-- TRANSFER BUTTON --}}
                <button
                    type="button"
                    wire:click="transferStudents"
                    @class([
                        'bg-gray-500 text-gray-300 text-sm px-2 rounded-lg shadow-lg ',
                        'bg-green-500 text-white' => ($schoolIdTo && $teacherIdTo && count($studentIdFroms))
                    ])
                    @disabled(! (count($studentIdFroms) && $schoolIdTo && $teacherIdTo))
                >
                    Transfer {{ count($studentIdFroms) }} Students
                </button>

            </fieldset>{{-- end of toVars --}}
        </div>

    </div>{{-- END OF ID=CONTAINER --}}

</div>


