<div style="display: flex; flex-direction: column;">
    @php( $dt = date('d M, Y g:i:s'))
    @php($path = Storage::disk('s3')->url($storedFileName))
    <div>Csv upload named:
        <a href="{{ $path }}">
            {{ $path }}
        </a>
    </div>
    <div>from: {{ $teacherUser->name }}</div>
    <div>{{ $teacherUser->email }}</div>
    <div>received on: {{ $dt }}.</div>
</div>
