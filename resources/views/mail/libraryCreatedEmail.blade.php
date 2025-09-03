<div>
    <label>A new library has been created:</label>
    <div>
        <ul style="margin-left:4rem; list-style-type:disc;">
            <li>
                id: {{ $library->id }}
            </li>
            <li>name: {{ $library->name }}</li>
            <li>school: {{ $library->school ? $libaray->school->name : 'Home'}}</li>
            <li>teacher: {{ $library->getTeacherName() }}</li>
            <li>teacher email: {{ $library->getTeacherEmail() }}</li>

        </ul>
    </div>
    <div>
        Send congrats! email
    </div>
</div>
