<div>
    <div>Hi, {{ $firstName }}</div>
    <div>The following teacher has requested an invitation to participate in
        the upcoming {{ $versionName }}:
    </div>
    <ul>
        <li>{{ $schoolVo }}</li>
        <li>{{ $schoolName }} is located in {{ $schoolCounty }} county.</li>
        <li>{{ $requesterFirstName }} can be reached by email at {{ $requesterEmail }}.</li>
    </ul>

    <div>
        <a href="{{$verificationUrl}}">
            <button type="button" class="bg-green-500 text-white rounded-full px-3 py-1">
                Please click here to verify this work email address
            </button>
        </a>
    </div>
    <div>
        <p>
            Note: You will be <b>denied</b> access to student-entered information while your work email is
            unverified.<br/>
            Additionally, access to participate in events through TheDirectorsRoom.com may be limited.
        </p>
    </div>
</div>
