<div>
    <style>
        .pageBreak {
            page-break-after: always;
        }
    </style>
    @foreach($dto['rooms'] AS $room)
        @include('pdfs.adjudications.backupPapers.events.25.singleRoom')
        <div class="pageBreak"></div>
    @endforeach
</div>
