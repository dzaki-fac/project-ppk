<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Tubes PPK Web'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Inter+Tight:wght@400;500&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .font-display { font-family: 'Inter Tight', 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .hl {
            color: #3398e1;
            background-color: #c1e1f7;
            padding: 2px 8px;
            border-radius: 4px;
            white-space: nowrap;
        }
        .seline-input:focus {
            outline: none;
            border-color: #3ba6f1 !important;
            box-shadow: 0 0 0 2px #3ba6f1;
        }
    </style>
</head>
<body class="bg-[#fafaf9] text-[#0c0a09] antialiased min-h-screen flex flex-col text-[14px] leading-[1.64]">
    {{-- Top nav: logo kiri, link tengah, aksi kanan --}}
    <header class="border-b border-[#e8e6e5] bg-[#fafaf9]">
        <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8 flex h-16 items-center justify-between gap-4">
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-[14px] font-medium text-[#0c0a09]">
                <svg class="h-4 w-4 text-[#0c0a09]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2c.6 3.5 2.1 5.6 4.3 7.3 1.5 1.1 3.2 2.1 3.2 4.2a5.5 5.5 0 0 1-11 0c0-1.5.8-2.7 1.8-3.8.4 1 1 1.9 2 2.5-.3-2.3.1-4.7 1-6.8.5-1.2 1.1-2.4 1.7-3.4z"/></svg>
                <span>{{ config('app.name', 'Tubes PPK') }}</span>
            </a>

            <nav class="hidden md:flex items-center">
                <a href="{{ url('/') }}" class="px-3 h-8 inline-flex items-center text-[14px] text-[#78716c] hover:text-[#0c0a09]">Beranda</a>
                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="px-3 h-8 inline-flex items-center text-[14px] text-[#78716c] hover:text-[#0c0a09]">Dashboard</a>
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('users.index') }}" class="px-3 h-8 inline-flex items-center text-[14px] text-[#78716c] hover:text-[#0c0a09]">Users</a>
                    @endif
                @endauth
            </nav>

            <div class="flex items-center gap-2">
                @auth
                    <x-profile-popup :name="auth()->user()->name" :email="auth()->user()->email" :role="auth()->user()->role" />
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-[14px] text-[#0c0a09] hover:underline">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-[14px] font-medium text-white bg-[#3ba6f1] border border-[#3398e1] rounded-full hover:brightness-95">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-1 w-full">
        @yield('content')
    </main>

    <footer class="border-t border-[#e8e6e5] py-6 text-center text-[12px] text-[#78716c]">
        &copy; {{ date('Y') }} {{ config('app.name', 'Tubes PPK Web') }}
    </footer>
</body>
</html>
