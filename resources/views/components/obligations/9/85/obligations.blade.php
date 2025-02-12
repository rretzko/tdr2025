<ul class="list-disc ml-8">
    <li class="mb-2">
        I understand that I have a responsibility to the NJ All-State Chorus audition submission process of
        all materials which include three Recording (MP3) files per singer in addition to their properly signed and
        <b><u>printed</u></b> (four signatures) application (<b><u>NOTHING HANDWRITTEN</u></b>) and
        ${{ $registrationFee }} fee.
    </li>

    <li class="mb-2">
        I understand that I must be prepared to serve as an Online (MP3) Adjudicator every year.
    </li>

    <li class="mb-2">
        I agree to listen to every student’s three Recording (MP3) submissions (Scales, Solo, Swan) in their
        entirety. In addition to listening for accuracy of performance, I will confirm that their Recording (MP3) is
        clearly audible. I will also confirm that the Voice Part on the MP3 is the Voice Part registered on the
        signed & printed (<b><u>NOTHING HANDWRITTEN</u></b>) Student Application.
    </li>

    <li class="mb-2">
        I understand I must <b><u>APPROVE</u></b> (CHECK ALL BOXES) and <b><u>SUBMIT</u></b> each
        Recording (MP3) file so they will BE PRINTED ON THE TEACHER ESTIMATE FORM (<b><u>NOTHING HANDWRITTEN</u></b>)
        and be locked into the system for adjudication.
    </li>

    <li class="mb-2">
        I understand that <b>THERE ARE NO VOICE PART CHANGES AFTER THE FINAL DIRECTOR DEADLINE/POSTMARK DEADLINE</b>
        - {{ $finalTeacherChanges }} Friday April 4th. If the Recording (MP3) is unacceptable or I wish to change the
        voice part, I can <b><u>REJECT</u></b>
        the Recording (MP3). If I <b><u>REJECT</u></b> the Recording (MP3) file, it will no longer be accessible.
    </li>

    <li class="mb-2">
        In accordance with the standard Internet Use Policy, I will confirm that there is nothing inappropriate or lewd
        on any Recording (MP3) submitted by my students.
    </li>

    <li class="mb-2">
        Please Note: MP3 Recordings will not be reviewed by registration managers. If the uploaded MP3 is an incorrect
        MP3 Recording or uploaded in the wrong place, it will receive a score of all 9’s.
    </li>

    <li class="mb-2">
        If I have students accepted into either the MIXED CHORUS or TREBLE CHORUS, I have a SECOND responsibility at a
        rehearsal or either concert. TWO total duties. A SECOND duty may include the following: attendance-sign
        in/sign out, testing, sectional rehearsals (either playing parts or conducting) or supervising students during
        rehearsals and/or concerts.
    </li>

    <li class="mb-2">
        I understand that failure to fulfill my responsibilities to the 2025/2026 NJ All-State Chorus will result in
        forfeiture of my students' participation in next-year's auditions.
    </li>

    @if($versionSchoolCounty)
        <li class="mb-2 bg-yellow-300">
            <div class=" bg-yellow-300">
                {{ $schoolName }} is in <b>{{ $schoolCountyName }}</b> county.
            </div>
            @if($schoolCountyName === 'Unknown')
                <div class=" text-red-500">
                    Your school county is not a valid name. Please return to the
                    <a href="{{ route('schools') }}" class="underline font-semibold">Schools page</a> to edit this
                    value.
                </div>
            @else
                <div class=" text-red-500">
                    If your school county is incorrect, please return to the
                    <a href="{{ route('schools') }}" class="underline font-semibold">Schools page</a> to edit this
                    value.
                </div>
            @endif
        </li>
    @endif

</ul>
