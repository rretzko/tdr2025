@props(['href', 'icon', 'label'])

@php
    $icons = [
        'map' => 'M9 20l-5.447-2.724A2 2 0 013 15.382V5.618a2 2 0 011.553-1.894L9 1m6 0l5.447 2.724A2 2 0 0121 5.618v9.764a2 2 0 01-1.553 1.894L15 20m0 0v4m-6-4v4',
        'building' => 'M3 10l9-6 9 6-9 6-9-6z M21 10v10a2 2 0 01-2 2H5a2 2 0 01-2-2V10',
        'academic-cap' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.84 6.834A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-3.588 12.083 12.083 0 01.84-6.834L12 14z',
        'user-group' => 'M17 20h5V4H2v16h5 M12 10a3 3 0 110-6 3 3 0 010 6z M12 13v7 M9 20h6',
        'book' => 'M4 19.5A2.5 2.5 0 006.5 22h11a2.5 2.5 0 002.5-2.5V5.5A2.5 2.5 0 0017.5 3h-11A2.5 2.5 0 004 5.5v14z',
        'ticket' => 'M9 19V6h13M9 6L3 9l6 3',
        'calendar' => 'M8 7V3M16 7V3M3 11h18M5 19h14a2 2 0 002-2V7H3v10a2 2 0 002 2z',
        'user-circle' => 'M12 14c3.866 0 7 3.134 7 7H5c0-3.866 3.134-7 7-7z M12 8a4 4 0 110-8 4 4 0 010 8z'
    ];
@endphp

<a href="{{ $href }}"
   class="flex items-center text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-yellow-400">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="{{ $icons[$icon] ?? '' }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
    </svg>
    {{ $label }}
</a>
