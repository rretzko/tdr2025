@props([
    'message' => 'Saved!',
])
<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 3000)"
    x-transition:leave.duration.500ms
    class="ml-2 text-green-600 italic"
>
    <div class="flex items-center">
        {{ $message }}
    </div>
</div>
