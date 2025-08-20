@props([
    'width' => null,
])
{{--<img src="https://auditionsuite-production.s3.amazonaws.com/thumbnails/swirlGclef_thumb.png"--}}
{{--     @if($width) width="{{$width}}" @endif--}}
{{--     alt="g-clef thumbnail image"--}}
{{--/>--}}

{{--<img src="{{ Storage::disk('s3')->url('backgrounds/tdr-monogram-raisedT.svg') }}"--}}
{{--     @if($width) width="{{$width}}" @endif--}}
{{--     alt="TDR monogram"--}}
{{--/>--}}

<img src="{{ Storage::disk('s3')->url('backgrounds/tdr-monogram-adjustedR.svg') }}"
     @if($width) width="{{$width}}" @endif
     alt="TDR monogram"
/>

