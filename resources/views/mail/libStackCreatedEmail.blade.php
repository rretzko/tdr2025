<div>
    <label>A new library item has been created:</label>
    <div>
        <ul style="margin-left:4rem; list-style-type:disc;">
            <li>library: {{ $library->name }}</li>
            <li>
                libItemId: {{ $libItem->id }}
            </li>
            <li>title: {{ $libItem->title }}</li>
            <li>type: {{ $libItem->item_type}}</li>
            <li>teacher: {{ $library->getTeacherName() }}</li>
            <li>teacher email: {{ $library->getTeacherEmail() }}</li>

        </ul>
    </div>
    <div>
        Send congrats on creating your first item! email
    </div>
</div>
