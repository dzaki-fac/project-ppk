<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] antialiased min-h-screen flex flex-col">
    {{-- Top Navbar dengan Pop-up Profil --}}
    <header class="sticky top-0 z-20 border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/80 dark:bg-[#161615]/80 backdrop-blur">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 flex h-16 items-center justify-between gap-4">
            {{-- Brand --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2 font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#F53003] text-white text-sm font-bold">PPK</span>
                <span class="hidden sm:inline">{{ config('app.name', 'Tubes PPK Web') }}</span>
            </a>

            {{-- Nav tengah (optional) --}}
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ url('/') }}" class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#F53003]">Beranda</a>
                <a href="#" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">Reservasi</a>
                <a href="#" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">Ruangan</a>
                <a href="#" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">Alat</a>
            </nav>

            {{-- Right: Profile Pop-up --}}
            <div class="flex items-center gap-2">
                @auth
                    <x-profile-popup :name="auth()->user()->name ?? auth()->user()->username" :email="auth()->user()->email" />
                @else
                    {{-- Demo mode: tampilkan pop-up profil demo + login link --}}
                    <div class="hidden sm:flex items-center gap-2">
                        <a href="{{ Route::has('login') ? route('login') : '#' }}" class="px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:underline">Log in</a>
                        <a href="{{ Route::has('register') ? route('register') : '#' }}" class="px-4 py-2 text-sm bg-[#1b1b18] dark:bg-[#eeeeec] dark:text-[#1C1C1A] text-white rounded-lg hover:bg-black">Register</a>
                    </div>
                    <x-profile-popup name="Demo User" email="demo@ppk.test" role="Mahasiswa" />
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] py-6 text-center text-xs text-[#706f6c] dark:text-[#A1A09A]">
        &copy; {{ date('Y') }} {{ config('app.name', 'Tubes PPK Web') }} — tubes-ppk-web
    </footer>
</body>
</html>
