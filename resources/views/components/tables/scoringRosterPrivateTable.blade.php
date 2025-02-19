<div class=" w-full">
    <style>
        #scores {
            border-collapse: collapse;
            font-size: 0.7rem;
            margin: auto;
            margin-top: 0.5rem;
            width: fit-content;
        }

        #scores td, th {
            padding: 0 0.25rem;
            border: 1px solid black;
            text-align: center;
        }

        #scores th.clearBorder {
            border: transparent;
        }
    </style>
    <table id="scores" class="text-xs">
        <thead>
        <tr>
            <th colspan="4" class="clearBorder"></th>
            @for($i=1; $i<=$judgeCount; $i++)
                <th colspan="{{ $factors->count() }}">
                    Judge {{ $i }}!
                </th>
            @endfor
            <th colspan="2" class="clearBorder"></th>
        </tr>
        <tr>
            <th colspan="4" class="text-center">{{ count($rows) }} candidates</th>
            @for($i=0; $i<$judgeCount; $i++)
                @forelse($categories AS $category)
                    <th colspan="{{ $category['colspan'] }}">
                        {{ $category['descr'] }}
                    </th>
                @empty
                    <th>No categories found</th>
                @endforelse
            @endfor
            <th colspan="2" class="clearBorder"></th>
        </tr>
        <tr>
            <th>Id</th>
            <th>VP</th>
            <th>Student</th>
            <th>School</th>
            @for($i=0; $i<$judgeCount; $i++)
                @forelse($factors AS $factor)
                    <th>{{ $factor->abbr }}</th>
                @empty
                    <th>No score factors found.</th>
                @endforelse
            @endfor
            <th>Total</th>
            <th>Result</th>
        </tr>
        </thead>
        <tbody>
        @forelse($rows AS $row)
            @php
                $rhs = str_replace('Regional High School', 'RHS', $row->schoolName);
                $schoolName = str_replace('High School', 'HS', $rhs);
            @endphp

            <tr class="hover:bg-green-100 hover:font-semibold">
                <td style="text-align: left;">{{ $row->id }}</td>
                <td style="text-align: left;">{{ $row->programName }}</td>
                <td style="text-align: left;">{{ substr($schoolName, 0, 12) }}</td>
                <td>{{ $row->voicePartAbbr }}</td>
                @if(isset($rowsScores[$row->id]))
                    @forelse($rowsScores[$row->id] AS $score)
                        <td class=" @if($score === 0) text-gray-300 @endif ">
                            {{ $score }}
                        </td>
                    @empty
                        <td>No scores found</td>
                    @endforelse
                @else
                    @for($i=0; $i<$factors->count() * $judgeCount; $i++)
                        <td class="text-gray-300 ">
                            0
                        </td>
                    @endfor
                @endif
                <td class=" @if(! $row->total) text-gray-300 @endif">
                    {{ $row->total ?: 0 }}
                </td>
                <td>
                    {{ $row->acceptance_abbr ?: 'ns' }}
                </td>
            </tr>
        @empty
            <tr>
                <td>No candidates found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
