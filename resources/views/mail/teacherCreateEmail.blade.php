<div>
    <label>A new teacher has been created:</label>
    <div>
        <ul style="margin-left:4rem; list-style-type:disc;">
            <li>
                id: {{ $teacher->user_id }}
            </li>
            <li>name: {{ $teacher->user->name }}</li>
        </ul>
    </div>
</div>
