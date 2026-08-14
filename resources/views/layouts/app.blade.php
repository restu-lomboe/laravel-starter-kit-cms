<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css"
        integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    <script>
        (function() {
            try {
                var saved = localStorage.getItem('anchor-hr-theme');
                var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (saved ? saved === 'dark' : prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>
</head>

<body class="min-h-screen font-sans text-paper antialiased">

    {{-- check if prefix admin --}}
    @if (Route::is('admin.*'))
        <div>
            <div class="min-h-screen flex">

                <!-- SIDEBAR -->
                @include('layouts.sidebar')

                <!-- ================= MAIN ================= -->
                <div class="flex-1 min-w-0 flex flex-col">

                    <!-- HEADER -->
                    @include('layouts.header')

                    <!-- CONTENT SLOT -->
                    <div
                        class="h-[calc(100vh-65px)] overflow-y-auto overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-3 [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-amber/80 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-amber/80 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:border-2 [&::-webkit-scrollbar-thumb]:border-white dark:[&::-webkit-scrollbar-thumb]:border-neutral-700">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    @else
        {{ $slot }}
    @endif

    @livewireScripts
</body>

</html>
