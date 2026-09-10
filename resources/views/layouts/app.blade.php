<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Jara') — {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Inter+Tight:wght@400;500&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-[#fafaf9] text-[#0c0a09] antialiased min-h-screen flex flex-col" style="font-family:Inter,ui-sans-serif,system-ui,sans-serif">
    <header class="sticky top-0 z-20 border-b border-[#e8e6e5] bg-white/85 backdrop-blur">
        <div class="mx-auto max-w-[1200px] px-4 sm:px-6 flex h-16 items-center justify-between gap-4">
            <a href="{{ auth()->check() ? route('projects.index') : url('/') }}" class="flex items-center gap-2 text-[14px] font-medium text-[#0c0a09]">
                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#3ba6f1] text-white text-[11px] font-semibold">✦</span>
                <span>Jara</span>
            </a>
            <nav class="hidden md:flex items-center gap-1 text-[14px]">
                @auth
                    <a href="{{ route('projects.index') }}" class="px-3 h-8 inline-flex items-center rounded-full {{ request()->routeIs('projects.*') ? 'text-[#0c0a09] font-medium' : 'text-[#78716c] hover:text-[#0c0a09]' }}">Projects</a>
                @else
                    <a href="{{ url('/') }}" class="px-3 h-8 inline-flex items-center text-[#78716c] hover:text-[#0c0a09]">Beranda</a>
                @endauth
            </nav>
            <div class="flex items-center gap-2">
                @auth
                    <x-profile-popup :name="auth()->user()->name" :email="auth()->user()->email" />
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-[14px] text-[#78716c] hover:text-[#0c0a09]">Log in</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-[14px] font-medium text-white bg-[#3ba6f1] border border-[#3398e1] rounded-full hover:opacity-90">Mulai gratis</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-1">
        <div class="mx-auto max-w-[1200px] px-4 sm:px-6 py-10">
            @if (session('status'))
                <div class="mb-6 rounded-[10px] border border-[#e8e6e5] bg-white px-4 py-3 text-[14px] text-[#0c0a09] shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded-[10px] border border-[#e8e6e5] bg-white px-4 py-3 text-[14px] text-[#0c0a09]">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </main>

    <footer class="py-8 text-center text-[12px] text-[#78716c]">
        &copy; {{ date('Y') }} Jara — An Advanced To-Do List
    </footer>
</body>
</html>
