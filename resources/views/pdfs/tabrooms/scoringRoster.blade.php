<div>
    @php
        $categories = $dto['categoryColspans'];
        $factors = $dto['factors'];
        $judgeCount = $dto['judgeCount'];
        $cntr = 0;
    @endphp

    <style>
        .pageBreak {
            page-break-after: always
        }
    </style>

    @foreach($dto['rows'] AS $key => $rows)
        @if($cntr)
            <div class="pageBreak"></div>
        @endif
        <h1 style="font-weight: bold; text-align: center; font-size: 2rem;">
            Score Roster Header
        </h1>
        <div style="font-size: 0.8rem;">
            @include('components.tables.scoringRosterTable')
        </div>
        @php $cntr++; @endphp
    @endforeach

</div>
