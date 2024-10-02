<div class="bg-white border hover:border-gray-500">

    <div class="border hover:border-gray-500 pb-2">

        <div class="px-4 sm:px-6 lg:px-8">

            <div class="mt-4">

                {{-- HEADER --}}
                <header
                    style="display: flex; flex-direction: row; justify-content: space-between; margin: 0 0.5rem 0.5rem;">
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

                        {{-- SEND TO: --}}
                        <div style="text-align: center; border-bottom: 1px solid gray;">
                            <div style="text-align: center;">
                                {{--                                Send to: Emily Kneuer, Treasurer--}}
                                Send to: Beth Moore, Treasurer
                            </div>
                            <div style="text-align: center; font-weight: bold;">
                                {{--                                Raritan High School--}}
                                Central Regional High School
                            </div>
                            <div style="text-align: center;">
                                {{--                                419 Middle Road--}}
                                509 Forest Hills Parkway
                            </div>
                            <div style="text-align: center;">
                                {{--                                Hazlet, NJ 07730--}}
                                Bayville, NJ 08721
                            </div>
                        </div>

                        {{-- INVOICE HEADER --}}
                        <div id="invoiceHeader">
                            <div style="text-align: center; font-weight: bold; border-bottom: gray;">
                                INVOICE
                            </div>
                            <div style="text-align: center; font-weight: bold;">
                                {{ $dto['versionName'] }}
                            </div>
                            <div style="text-align: center; font-weight: bold; margin-bottom: 1rem;">
                                STUDENT AUDITION FEE INVOICE
                            </div>
                            <div style="text-align: center; font-weight: bold; margin-bottom: 1rem;">
                                ${{ $dto['registrationFee'] }} Per Student
                            </div>
                            <div style="text-align: center; font-weight: bold; margin-bottom: 1rem;">
                                DUE: November 3, 2024
                            </div>
                            <div style="text-align: center; font-weight: bold;">
                                Please send one check for all students auditioning at your school.<br/>
                                Please make out all checks to "All-Shore Chorus Inc."
                            </div>
                            @include('components.forms.partials.timestamp')
                        </div>

                        {{-- INVOICE DETAIL --}}
                        <div id="invoiceDetail">
                            <style>
                                td, th {
                                    text-align: left;
                                    padding-left: 0.5rem;
                                    border: white;
                                }

                                th {
                                    font-weight: bold;
                                }
                            </style>
                            <table style="margin-top: 1rem;">
                                <tbody>
                                <tr>
                                    <td>School</td>
                                    <th>{{ $dto['schoolName'] }}</th>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <th>{{ \Carbon\Carbon::now()->format('D, M d, Y h:i a') }}</th>
                                </tr>
                                <tr>
                                    <td>Number of Students Auditioning</td>
                                    <th>{{ count($dto['registrants']) }}</th>
                                </tr>
                                <tr>
                                    <td>Choral Director</td>
                                    <th>{{ $dto['teacherFullName'] }}</th>
                                </tr>
                                <tr>
                                    <td>Total amount enclosed ({{ count($dto['registrants']) }} x
                                        ${{ $dto['registrationFee'] }})
                                    </td>
                                    <th>${{ count($dto['registrants']) * $dto['registrationFee']}}</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </header>

                {{-- DATA SECTION --}}
                <section id="table" style="margin-bottom: 1rem;">
                    <style>
                        table {
                            border-collapse: collapse;
                            margin: auto auto 1rem;
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
                                <th>{{ $dto['registrationFee'] }}</th>
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
            </div>
        </div>
    </div>

    {{--                <section id="voicePartSummary">--}}

    {{--                    <table>--}}
    {{--                        <thead>--}}
    {{--                        <tr>--}}
    {{--                            <th></th>--}}
    {{--                            @foreach($dto['voiceParts'] AS $key => $voicepart)--}}
    {{--                                <th style="background-color: {{ ((! ($key % 2)) ? 'lightgray' : 'white') }}">--}}
    {{--                                    {{ strtoupper($voicepart['abbr']) }}--}}
    {{--                                </th>--}}
    {{--                            @endforeach--}}

    {{--                            --}}{{-- EPAYMENTS LABEL --}}
    {{--                            @if($dto['ePaymentsAllowed'])--}}
    {{--                                <th>ePayments</th>--}}
    {{--                            @endif--}}

    {{--                            <th>Total Due</th>--}}
    {{--                        </tr>--}}
    {{--                        </thead>--}}
    {{--                        <tbody>--}}
    {{--                        <tr>--}}
    {{--                            <th>Voice Part Totals</th>--}}
    {{--                            @foreach($dto['voiceParts'] AS $key => $voicepart)--}}
    {{--                                <td style="text-align: center; background-color: {{ ((! ($key % 2)) ? 'lightgray' : 'white') }}">--}}
    {{--                                    {{ $voicepart['count'] }}--}}
    {{--                                </td>--}}
    {{--                            @endforeach--}}

    {{--                            --}}{{-- EPAYMENTS --}}
    {{--                            @if($dto['ePaymentsAllowed'])--}}
    {{--                                <th>{{ $dto['ePaymentsUsd'] }}</th>--}}
    {{--                            @endif--}}

    {{--                            <th>${{ $dto['totalDue'] }}</th>--}}
    {{--                        </tr>--}}
    {{--                        </tbody>--}}
    {{--                    </table>--}}

    {{--                </section>--}}


</div>


