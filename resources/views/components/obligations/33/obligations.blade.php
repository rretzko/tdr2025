<ul class="list-disc ml-8">
    <li class="mb-2">
        <b>I understand</b> that I must be prepared to serve as a Recording (MP3) Adjudicator every year.
    </li>

    <li class="mb-2">
        <b>If I have students accepted</b> into either the MIXED CHORUS or TREBLE CHORUS, I have a SECOND responsibility
        to assist at a rehearsal (sectionals, testing) or the concert. TWO total duties.
    </li>
    <li class="mb-2">
        <b>I agree to listen</b> to every student’s Recording (MP3) submission in its entirety. In addition to listening
        for accuracy of performance, I will confirm that their Recording (MP3) is clearly audible. I will also confirm
        that the Voice Part on the MP3 is the Voice Part registered on the signed Student Application. In accordance
        with
        standard Internet Use Policy, I will also confirm that there is nothing inappropriate or lewd on any Recording
        (MP3)
        submitted by my students.
        <ul>
            <li>
                <b>Please note: MP3 Recordings will not be reviewed by registration managers. If the uploaded MP3 is an
                    incorrect MP3 Recording, it will receive a score of all 9’s.</b>
            </li>
        </ul>
    </li>
    <li class="mb-2">
        <b>I understand</b> that I may be asked as a SECOND duty to assist (attendance-sign in/sign out, testing,
        supervising students, sectional rehearsals (either playing parts or conducting) as well as supervising the
        students
        during rehearsals and/or concerts.
    </li>

    <li class="mb-2">
        <b>Should I need</b> to send a substitute, I understand that my substitute must be a NAfME Member in good
        standing.
    </li>

    <li class="mb-2">
        <b>I understand</b> that failure to fulfill my responsibilities to the {{ $versionShortName }}
        <span class="underline">will result in forfeiture</span> of my students' participation the next-year's
        auditions.
    </li>

    @if($versionSchoolCounty)
        <li class="mb-2">
            <div>{{ $schoolName }} is in <b>{{ $schoolCountyName }}</b> county.</div>
            @if($schoolCountyName === 'Unknown')
                <div class="text-red-500">
                    Your school county is not a valid name. Please return to the
                    <a href="{{ route('schools') }}" class="underline font-semibold">Schools page</a> to edit this
                    value.
                </div>
            @else
                <div class="text-red-500">
                    If your school county is incorrect, please return to the
                    <a href="{{ route('schools') }}" class="underline font-semibold">Schools page</a> to edit this
                    value.
                </div>
            @endif
        </li>
    @endif

</ul>
