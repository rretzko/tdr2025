@props([
    'label' => '',
    'value' => '',
    'color' => 'default',
])
@php
    $colors = [
        'green'   => 'bg-green-50 border-green-300 text-green-800 dark:bg-green-900/20 dark:text-green-300',
        'red'     => 'bg-red-50 border-red-300 text-red-800 dark:bg-red-900/20 dark:text-red-300',
        'blue'    => 'bg-blue-50 border-blue-300 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
        'yellow'  => 'bg-yellow-50 border-yellow-300 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
        'default' => 'bg-gray-50 border-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
    ];
    $cls = $colors[$color] ?? $colors['default'];
@endphp
<div class="border rounded-lg px-4 py-3 text-center {{ $cls }}">
    <div class="text-2xl font-bold">{{ $value }}</div>
    <div class="text-xs mt-1 opacity-80">{{ $label }}</div>
</div>
