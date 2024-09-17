<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 3000)"
    x-transition.leave.duration.500ms
    class="ml-2 text-green-600 italic"
>
    Saved!
</div>
