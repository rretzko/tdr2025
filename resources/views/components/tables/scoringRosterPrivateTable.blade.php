<div class="">
    <style>
        #scores {
            /*background-color: rosybrown;*/
            border-collapse: collapse;
            font-size: 0.7rem;
            margin: auto;
            margin-top: 0.5rem;
            table-layout: fixed;
            width: 90%;
            max-width: 98%;
        }

        #scores td, th {
            padding: 0 0.25rem;
            border: 1px solid black;
            text-align: center;
        }

        #scores td.fixedWidth, th.fixedWidth {
            width: 10rem;
            min-width: 10rem;
            max-width: 10rem;
        }

        #scores th.clearBorder {
            border: transparent;
        }
    </style>
    <table id="scores" class="text-xs ">
        <thead>
        <tr>
            <th class="clearBorder" style="width: 3rem;"></th>
            <th class="clearBorder fixedWidth"></th>
            <th class="clearBorder fixedWidth"></th>
            <th class="clearBorder" style="width: 2rem;"></th>
            @for($i=1; $i<=$judgeCount; $i++)
                <th colspan="{{ $factors->count() }}">
                    Judge {{ $i }}
                </th>
            @endfor
            <th class="clearBorder" style="width: 2rem;"></th>
            <th class="clearBorder" style="width: 3rem;"></th>
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
            <th class="fixedWidth">
                Student
            </th>
            <th class="fixedWidth;">
                School
            </th>
            <th>VP</th>
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
            @if(!($loop->iteration % 20))
                <div style="page-break-after: always"></div>
            @endif

            <tr class="hover:bg-green-100 hover:font-semibold">
                <td style="text-align: left;">{{ $row->id }}</td>
                <td class="fixedWidth" style="text-align: left;">
                    <div>{{ $row->programName }}</div>
                    <div
                        style="margin-left: 0.25rem; font-style: italic;  white-space: normal; overflow-wrap: break-word; ">
                        {{ $row->studentEmail }}
                    </div>
                </td>
                <td class="fixedWidth" style="text-align: left;">
                    <div>{{ substr($schoolName, 0, 12) }}</div>
                    <div style="margin-left: 0.25rem; font-style: italic;">
                        {{ $row->teacherName }}
                    </div>
                    <div
                        style="margin-left: 0.25rem; font-style: italic;  white-space: normal; overflow-wrap: break-word;">
                        {{ $row->teacherEmail }}
                    </div>
                </td>
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
