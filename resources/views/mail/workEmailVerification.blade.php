<div>
    <div>Hi, {{ $name }}</div>
    <div>We have updated your Schools roster as follows:{{ $schoolVo }}</div>
    <div>Your work email is: {{ $workEmail }}</div>
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
