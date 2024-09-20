@props([
    'id' => 0,
    'route' => 'home',
    'disabled' => false,
])
<a href="{{ ($id) ? route($route, ['event' => $id]) : route($route) }}" tabindex="-1">
    <button
        type="button"
        class="bg-green-500 text-white text-3xl px-2 rounded-lg"
        title="Add New"
        tabindex="-1"
        @disabled($disabled)
    >
        +
    </button>
</a>
