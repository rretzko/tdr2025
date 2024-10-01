<div>

    @foreach($dto['rooms'] AS $room)

        @forelse($room['judges'] AS $judges)

            @forelse($judges AS $judge)
                @include('pdfs.adjudications.backupPapers.header')
                @include('pdfs.adjudications.backupPapers.judgeHeader')
                <h3 style="text-align: center; font-size: 0.8rem; margin-top: -1rem;">Page
                    1/{{ $room['pageCount'] }}</h3>
                @include('pdfs.adjudications.backupPapers.scoresTable')
                @include('pdfs.adjudications.backupPapers.footer')
            @empty
                @include('pdfs.adjudications.backupPapers.header')
                @include('pdfs.adjudications.backupPapers.footer')
            @endforelse
        @empty
            @include('pdfs.adjudications.backupPapers.header')
            <div style="text-align: center;">
                No Judges found.<br/>
                Please return to the <i>Version Dashboard-Judge Assignment</i> card to add judges
            </div>
            @include('pdfs.adjudications.backupPapers.footer')
        @endforelse

    @endforeach
</div>
