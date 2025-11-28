<div class="bg-white border hover:border-gray-500">

    <div class="border hover:border-gray-500 pb-2">

        <div class="px-4 sm:px-6 lg:px-8">

            <div class="mt-4">

                {{-- HEADER --}}
                <header
                    style="display: flex; flex-direction: row; justify-content: space-between; margin: 0 0.5rem; margin-bottom: 0.5rem;">
                    <div>
                        @if($dto['logo'])
                            {{-- https://auditionsuite-production.s3.amazonaws.com/logos/testPublic.jpg --}}
                            <img src="{{ Storage::disk('s3')->url($dto['logo']) }}"
                                 alt="{{ $dto['organization'] }} logo" height="60" width="60"/>
                        @endif
                    </div>

                    <div style="display: flex; flex-direction: column; justify-content: center;">
                        <div
                            style="text-align: center; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid darkgray;">
                            {{ $dto['versionName'] }}
                        </div>
                        <div style="text-align: center; font-weight: bold; text-transform: uppercase;">
                            {{ $dto['seniorClassOf'] }} TEACHER ESTIMATE FORM
                        </div>
                        <div style="text-align: center;">
                            {{ $dto['teacherFullName'] }}
                        </div>
                        <div style="text-align: center">
                            {{ $dto['schoolName'] }}
                        </div>

                        @include('components.forms.partials.timestamp')

                    </div>

                </header>

                {{-- DATA SECTION --}}
                <header style="text-align: center; border-bottom: 1px solid black; margin-bottom: 1rem;">
                    @if($dto['maxCount'])
                        {{ $dto['maxCount'] }} STUDENTS MAXIMUM
                    @endif
                </header>

                <section id="table" style="margin-bottom: 1rem;">
                    <style>
                        table {
                            border-collapse: collapse;
                            margin: auto;
                            margin-bottom: 1rem;
                            width: 98%;
                        }

                        td, th {
                            padding: 0 0.25rem;
                            border: 1px solid darkgray;
                        }
                    </style>
                    <table style="width: 98%; margin: auto;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Voice Part</th>
                            <th>Grade</th>
                            <th>Fee</th>
                        </tr>
                        </thead>
                        @forelse($dto['registrants'] AS $registrant)
                            <tr style="background-color: {{ ($loop->odd ? 'rgba(0,255,0,0.1)' : 'white') }};">
                                <td style="text-align: right">{{ $loop->iteration }}</td>
                                <th>{{ $registrant->last_name }}</th>
                                <th>{{ $registrant->first_name }}</th>
                                <th>{{ $registrant->voicePartDescr }}</th>
                                <th>{{ $registrant->grade }}</th>
                                <th>{{ $dto['registrationFee'] * $loop->iteration }}</th>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center;">
                                    No registrants found.
                                </td>
                            </tr>
                        @endforelse
                    </table>
                </section>

                <section id="voicePartSummary">

                    <table>
                        <thead>
                        <tr>
                            <th></th>
                            @foreach($dto['voiceParts'] AS $key => $voicepart)
                                <th style="background-color: {{ ((! ($key % 2)) ? 'lightgray' : 'white') }}">
                                    {{ strtoupper($voicepart['abbr']) }}
                                </th>
                            @endforeach

                            {{-- EPAYMENTS LABEL --}}
                            @if($dto['ePaymentsAllowed'])
                                <th>ePayments</th>
                            @endif

                            <th>Total Due</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>Voice Part Totals</th>
                            @foreach($dto['voiceParts'] AS $key => $voicepart)
                                <td style="text-align: center; background-color: {{ ((! ($key % 2)) ? 'lightgray' : 'white') }}">
                                    {{ $voicepart['count'] }}
                                </td>
                            @endforeach

                            {{-- EPAYMENTS --}}
                            @if($dto['ePaymentsAllowed'])
                                <th>{{ $dto['ePaymentsUsd'] }}</th>
                            @endif

                            <th>${{ $dto['totalDue'] }}</th>
                        </tr>
                        </tbody>
                    </table>

                </section>


            </div>

            {{-- ORGANIZATION CARD --}}
            <div style="page-break-before: always">
                <header
                    style="display: flex; flex-direction: row; justify-content: space-between; margin: 0 0.5rem; margin-bottom: 0.5rem;">
                    <div>
                        @if($dto['logo'])
                            {{-- https://auditionsuite-production.s3.amazonaws.com/logos/testPublic.jpg --}}
                            <img src="{{ Storage::disk('s3')->url($dto['logo']) }}"
                                 alt="{{ $dto['organization'] }} logo" height="60" width="60"/>
                        @endif
                    </div>

                    <div style="display: flex; flex-direction: column; justify-content: center;">
                        <div
                            style="text-align: center; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid darkgray;">
                            {{ $dto['versionName'] }}
                        </div>
                        <div style="text-align: center; font-weight: bold; text-transform: uppercase;">
                            {{ $dto['seniorClassOf'] }} TEACHER ESTIMATE FORM
                        </div>
                        <div style="text-align: center;">
                            {{ $dto['teacherFullName'] }}
                        </div>
                        <div style="text-align: center">
                            {{ $dto['schoolName'] }}
                        </div>

                    </div>

                </header>

                <div
                    style="margin-top: 12rem; margin-left: 25%; width: 50%; border: 1px solid black; text-align: center; height: 12rem; background-color: lightgray">
                    <div style="margin-top: 5rem;">
                        Attach NAfME CARD here...
                    </div>
                </div>

            </div>

            {{-- MAILING INSTRUCTIONS --}}
            <div style="page-break-before: always;">
                <div style="border: 1px solid black; padding: 0.25px; text-align: center; width: 66%; margin: auto;">
                    <div style="border-bottom:1px solid darkgray; margin-bottom: 0.5rem;">Mail to:</div>
                    @foreach($dto['coregistrationManagerAddressArray'] AS $component)
                        <div>{{ $component }}</div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>


