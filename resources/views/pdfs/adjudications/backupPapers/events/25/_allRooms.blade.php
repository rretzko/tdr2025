<div>

    @foreach($dto['rooms'] AS $room)

        @forelse($room['judges'] AS $judges)

            @forelse($judges AS $judge)
                @include('pdfs.adjudications.backupPapers.header')
                @include('pdfs.adjudications.backupPapers.judgeHeader')
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
