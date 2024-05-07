@props([
    'width' => null,
])
<img src="https://auditionsuite-production.s3.amazonaws.com/thumbnails/swirlGclef_thumb.png"
     @if($width) width="{{$width}}" @endif
     alt="g-clef thumbnail image"
/>

