<div>

    <style>
        .pageBreak {
            page-break-after: always
        }
    </style>

    @php
        $cntr = 0;
    @endphp

    @foreach($dto['rows'] AS $key => $rows)
        @if($cntr)
            <div class="pageBreak"></div>
        @endif
        <h1 style="font-weight: bold; text-align: center; font-size: 1rem;">
            {{ $dto['versionName'] }}<br/>
            <span class="font-size: 1rem;">{{ $dto['voicePartDescr'] }}</span>
        </h1>
        <div style="font-size: 0.8rem;">
            @php
                $categories = $dto['categoryColspans'];
                $factors = $dto['factors'];
                $judgeCount = $dto['judgeCount'];
            @endphp
            @include('components.tables.scoringRosterPrivatePdfTable')
        </div>
        @php $cntr++; @endphp
    @endforeach

</div>
