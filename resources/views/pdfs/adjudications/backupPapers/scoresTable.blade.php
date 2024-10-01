<div style="width: 100%;">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid black;
            padding: 0 0.25rem;
        }

        table td:last-child {
            width: 100%;
        }

        th.headerTopLeft {
            border-top: 0;
            border-left: 0;
        }

        th.headerTopRight {
            border-top: 0;
            border-right: 0;
        }
    </style>

    {{-- TABLE HEADER --}}
    @include('pdfs.adjudications.backupPapers.tableHeader')

    {{-- REGISTRANTS --}}
    <tbody>
    @php($lineCounter=1)
    @php($pageCounter=1)

    @forelse($room['registrants'] AS $registrant)
        @if($lineCounter > 30)
            {{-- CLOSE THE TABLE --}}
    </tbody>
    </table>
    {{-- CLOSE THE PAGE --}}
    @include('pdfs.adjudications.backupPapers.footer')
    {{-- START A NEW PAGE --}}
    @include('pdfs.adjudications.backupPapers.header')
    @include('pdfs.adjudications.backupPapers.judgeHeader')
    @php($pageCounter++);
    <h3 style="text-align: center; font-size: 0.8rem; margin-top: -2rem;">Page {{ $pageCounter }}
        /{{ $room['pageCount'] }}</h3>
    {{-- START A NEW TABLE --}}
    @include('pdfs.adjudications.backupPapers.tableHeader')
    {{-- RESET $lineCounter --}}
    @php($lineCounter=1)
    @endif
    <tr>
        <td style="text-align: center;">
            {{ $loop->iteration }}
        </td>
        <td>
            {{ $registrant['id'] }}
        </td>
        <td style="text-align: center;">
            {{ $registrant['abbr'] }}
        </td>
        @for($i=0; $i<$room['factorCount']; $i++)
            <td></td>
        @endfor
        <td></td>
        <td></td>
        @php($lineCounter++)
    </tr>

    @empty
        <tr>
            <td colspan="11" style="text-align: center;">
                No registrants found.
            </td>
        </tr>
        @endforelse
        </tbody>
        </table>
</div>
