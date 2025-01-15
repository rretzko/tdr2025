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
        <a href="{{$verificationUrl}}" class="mx-auto bg-green-500 text-white rounded-full" style="cursor:pointer">
            <button type="button"
                    style="background-color: royalblue; color: white; border-radius: 9999px;  padding: 0.5rem; cursor: pointer;">
                Please click here to invite {{ $requesterName }} to {{ $versionName }}.
            </button>
        </a>
    </div>

    <div style="margin-top:1rem;">
        Note: This button will expire in two days!
    </div>

</div>
