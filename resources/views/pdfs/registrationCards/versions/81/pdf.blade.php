<div>

    <style>
        .card {
            border-collapse: collapse;
            margin-bottom: 0.25rem;
            width: 100%;
        }

        td {
            padding: 0 0.25rem;
        }

        td.cardContent {
            border-right: 1px solid black;
            border-left: 1px solid black
        }

        td.cardContentBottomRow {
            display: block;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            border: 1px solid black;
            border-top: 1px solid white;
        }

        td.cardContentCentered {
            display: block;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            border-right: 1px solid black;
            border-left: 1px solid black
        }

        td.cardContentTopRow {
            border: 1px solid black;
            border-bottom: 1px solid white;
        }

        td.cardSpacer {
            border-top: 0;
            border-bottom: 0;
        }

        .pageBreak {
            page-break-after: always;
        }
    </style>
    @foreach($dto['rows'] AS $key => $registrant)

        {{-- ADD A PAGE BREAK IF $registrant INTRODUCES A NEW VOICEPARTDESCR --}}
        @if($key && ($dto['rows'][$key - 1]['voicePartDescr'] !== $registrant['voicePartDescr']))
            <div class="pageBreak"></div>
        @endif

        <table class="card">

            <tbody>

            {{-- VERSION NAME --}}
            <tr>
                <td class="cardContentTopRow">
                    {{ $dto['versionName'] }}
                </td>
                <td class="cardSpacer">{{-- blank --}}</td>
                <td class="cardContentTopRow">
                    {{ $dto['versionName'] }}
                </td>
            </tr>

            {{-- ROOM NAME --}}
            <tr>
                <td class="cardContent" style="">
                    {{ strtoupper($registrant['rooms'][0]->roomName) }}
                </td>
                <td class="cardSpacer">{{-- blank --}}</td>
                <td class="cardContent" style="">
                    @if(array_key_exists(1, $registrant['rooms']))
                        {{ strtoupper($registrant['rooms'][1]->roomName) }}
                    @else
                        <span style="color: rgba(0,0,0,0.3);">room name</span>
                    @endif {{-- else create "blank" card --}}
                </td>

            </tr>

            {{-- AUD# + VOICE PART DESCR --}}
            <tr>
                <td class="cardContent" style="font-weight: bold;">
                    <table style="background-color: rgba(0,0,0,0.1); width: 100%;">
                        <td style="text-align: left; font-weight: bold;">
                            Aud # {{ $registrant['ref'] }}
                        </td>
                        <td style="text-align: right; font-weight: bold;">
                            {{ $registrant['voicePartDescr'] }}
                        </td>
                    </table>
                </td>
                <td class="cardSpacer">{{-- blank --}}</td>
                <td class="cardContent" style="font-weight: bold;">
                    <table style="background-color: rgba(0,0,0,0.1); width: 100%;">
                        <td style="text-align: left; font-weight: bold;">
                            Aud # @if(array_key_exists(1, $registrant['rooms']))
                                {{ $registrant['ref'] }}
                            @else
                                <span style="color: rgba(0,0,0,0.3);"></span>
                            @endif {{-- else create "blank" card --}}
                        </td>
                        <td style="text-align: right; font-weight: bold;">
                            @if(array_key_exists(1, $registrant['rooms']))
                                {{ $registrant['voicePartDescr'] }}
                            @else
                                <span style="color: rgba(0,0,0,0.3);">voice part</span>
                            @endif {{-- else create "blank" card --}}
                        </td>
                    </table>
                </td>
            </tr>

            {{-- REGISTRANT NAME --}}
            <tr style="padding: 0.25rem 0;">
                <td class="cardContentCentered" style="font-size: 0.8rem; ">
                    {{ strtoupper($registrant['fullNameAlpha']) }}
                </td>
                <td class="cardSpacer">{{-- blank --}}</td>
                <td class="cardContentCentered" style="font-size: 0.8rem; ">
                    @if(array_key_exists(1, $registrant['rooms']))
                        {{ strtoupper($registrant['fullNameAlpha']) }}
                    @else
                        <span style="color: rgba(0,0,0,0.3);">name</span>
                    @endif {{-- else create "blank" card --}}
                </td>
            </tr>

            {{-- REGISTRANT EMAIL --}}
            <tr>
                <td class="cardContentCentered" style="font-size: 0.8rem;">
                    {{ $registrant['email'] }}
                </td>
                <td class="cardSpacer">{{-- blank --}}</td>
                <td class="cardContentCentered" style="font-size: 0.8rem; ">
                    @if(array_key_exists(1, $registrant['rooms']))
                        {{ $registrant['email'] }}
                    @else
                        <span style="color: rgba(0,0,0,0.3);">email</span>
                    @endif {{-- else create "blank" card --}}
                </td>
            </tr>

            {{-- TIMESLOT --}}
            <tr>
                <td class="cardContentCentered">
                    {{ $registrant['timeslot'] }}
                </td>
                <td class="cardSpacer">{{-- blank --}}</td>
                <td class="cardContentCentered">
                    @if(array_key_exists(1, $registrant['rooms']))
                        {{ $registrant['timeslot'] }}
                    @else
                        <span style="color: rgba(0,0,0,0.3);font-size: 0.8rem;">time</span>
                    @endif {{-- else create "blank" card --}}
                </td>
            </tr>

            {{-- SCHOOL NAME --}}
            <tr>
                <td class="cardContentCentered" style="font-size: 0.8rem;">
                    {{ $registrant['schoolName'] }}
                </td>
                <td class="cardSpacer">{{-- blank --}}</td>
                <td class="cardContentCentered" style="text-algin: center;  font-size: 0.8rem;">
                    @if(array_key_exists(1, $registrant['rooms']))
                        {{ $registrant['schoolName'] }}
                    @else
                        <span style="color: rgba(0,0,0,0.3);">school name</span>
                    @endif {{-- else create "blank" card --}}
                </td>
            </tr>

            {{-- SCHOOL ID --}}
            <tr>
                <td class="cardContentBottomRow">
                    {{ $registrant['schoolId'] }}
                </td>
                <td class="cardSpacer">{{-- blank --}}</td>
                <td class="cardContentBottomRow">
                    @if(array_key_exists(1, $registrant['rooms']))
                        {{ $registrant['schoolId'] }}
                    @else
                        <span style="color: rgba(0,0,0,0.3);font-size: 0.8rem;">school id</span>
                    @endif {{-- else create "blank" card --}}
                </td>
            </tr>

            </tbody>

        </table>

        <hr style="color: blue; margin: 0.5rem 0;"/>

    @endforeach

</div>
