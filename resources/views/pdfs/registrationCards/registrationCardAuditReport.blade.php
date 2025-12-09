<div>
    <h1>Registration Card Audit Report</h1>
    <h2>Registrant count: {{ count($registrants) }}</h2>

    <style>
        table {
            border-collapse: collapse;
            border: 1px solid black;
        }
        table, th, td {
            border: 1px solid black;
            padding:0 4px;
        }
        .email{
            font-size: 12px;
        }
        .schoolHeader{
            background-color: #ccc;
            font-size: 14px;
            font-weight: bold;
        }
    </style>

    @php($timeslot = "")
    @php($schoolName = "")

    <table>
        <thead>
        <tr>
            <th>###</th>
            <td style="border: 1px solid #000; padding: 4px; text-align: center">☑</td>
            <th>Id</th>
            <th>Student</th>
            <th>Voicing</th>
        </tr>
        </thead>
        <tbody>
    @forelse($registrants AS $registrant)
        @if(!($timeslot && $schoolName))
            @php($timeslot = $registrant->timeslot)
            @php($schoolName = $registrant->schoolName)
            <tr class="schoolHeader">
                <td colspan="2">
                    {{ $registrant->timeslot }}
                </td>
                <td colspan="3">
                    {{ $registrant->schoolName }}
                </td>
            </tr>
        @endif
        @if($timeslot && $schoolName)
            @if(!(($timeslot === $registrant->timeslot) && ($schoolName === $registrant->schoolName)))
                @php($timeslot = $registrant->timeslot)
                @php($schoolName = $registrant->schoolName)
                <tr class="schoolHeader">
                    <td colspan="2">
                        {{ $registrant->timeslot }}
                    </td>
                    <td colspan="3">
                        {{ $registrant->schoolName }}
                    </td>
                </tr>
            @endif
        @endif
        <tr>
            <td style="text-align: center">{{ $loop->iteration }}</td>
            <td></td>
            <td>{{ $registrant->id }}</td>
            <td>
                <div>{{ $registrant->alphaName }}</div>
                <div class="email">{{ $registrant->email }}</div>
            </td>
            <td class="text-align: center">{{ $registrant->voicePartAbbr }}</td>
        </tr>
    @empty
        <tr><td colspan="6">No students found</td></tr>
    @endforelse
        </tbody>
    </table>

</div>
