<!DOCTYPE html>
<html lang="en" class="transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <title>TDR Tutorials Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans transition-colors duration-300">
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white dark:bg-gray-800 shadow-lg p-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold">
                <a href="{{ route('tutorials.dashboard') }}">
                    TDR Tutorials
                </a>
            </h2>
            {{--            <button onclick="toggleDarkMode()" title="Toggle Dark Mode"--}}
            {{--                    class="text-gray-500 hover:text-yellow-300 dark:hover:text-yellow-400">--}}
            {{--                🌙--}}
            {{--            </button>--}}
        </div>
        <nav class="mt-6 space-y-4">
            {{--            <x-tutorial-link href="/tutorial/tdroverview" icon="map" label="TDR Overview" />--}}
            <x-tutorial-link href="/tutorial/schools" icon="building" label="Schools"/>
            <x-tutorial-link href="/tutorial/students" icon="academic-cap" label="Students"/>
            <x-tutorial-link href="/tutorial/ensembles" icon="user-group" label="Ensembles"/>
            <x-tutorial-link href="/tutorial/libraries" icon="book" label="Libraries"/>
            <x-tutorial-link href="/tutorial/programs" icon="ticket" label="Programs"/>
            <x-tutorial-link href="/tutorial/events" icon="calendar" label="Events"/>
            <x-tutorial-link href="/tutorial/profile" icon="user-circle" label="Profile"/>
        </nav>
    </aside>

    <!-- Page Content -->
    <div id="pageContent" class="flex flex-col w-full text-gray-100 text-lg">
        <header class="bg-gray-700 text-yellow-400 text-2xl w-full px-4 py-2 mb-0.5">
            <h1>{{ $header }}</h1>
        </header>

        <main class="bg-gray-700 min-h-screen w-full px-4 py-2">
            {{ $slot }}
        </main>

    </div>

    <script>
        if (
            localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        }

        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }
    </script>


    <x-site-footer/>
</div>
</body>
</html>
