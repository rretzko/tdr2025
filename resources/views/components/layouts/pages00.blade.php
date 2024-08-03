<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TDR.2025') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

{{--        <script src="https://unpkg.com/@alpinejs/ui@latest/dist/cdn.min.js" defer></script>--}}

</head>
<body class="font-sans antialiased">

<x-auth-header/>

<div class="min-h-screen bg-white dark:bg-gray-900">

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow md:mt-8 w-full bg-transparent border border-t-gray-100">
            <div class="max-w-7xl md:pl-8 md:py-4 px-4 sm:px-6 md:px-2">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Page Content -->
    <main class="bg-gray-700 min-h-screen">
        {{ $slot }}
    </main>

    <x-site-footer/>
</div>
</body>
</html>
