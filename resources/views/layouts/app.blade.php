<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Jara — ' . config('app.name', 'Laravel'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Inter+Tight:wght@400;500&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="min-h-screen flex flex-col" style="background:#fafaf9;color:#0c0a09;">
    {{-- Top nav — Seline style --}}
    <header class="sticky top-0 z-20 bg-white/90 backdrop-blur" style="border-bottom:1px solid #e8e6e5;">
        <div class="mx-auto flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8" style="max-width:1200px;">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm font-medium" style="color:#0c0a09;">
                <span class="flex h-7 w-7 items-center justify-center rounded-full text-white text-xs font-semibold" style="background:#3ba6f1;">J</span>
                <span class="font-display text-[18px] tracking-tight">Jara</span>
                <span class="hidden sm:inline rounded-full px-2 py-0.5 text-[10px] font-medium tracking-widest uppercase" style="border:1px solid #e8e6e5;color:#78716c;">To-Do List</span>
            </a>

            <nav class="hidden md:flex items-center gap-1 text-sm">
                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-full" style="{{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'background:#1c1917;color:#fff;' : 'color:#78716c;' }}">Dashboard</a>
                <a href="{{ route('projects.index') }}" class="px-3 py-2 rounded-full" style="{{ request()->routeIs('projects.*') ? 'background:#1c1917;color:#fff;' : 'color:#78716c;' }}">Projects</a>
                <a href="{{ route('tasks.index') }}" class="px-3 py-2 rounded-full" style="{{ request()->routeIs('tasks.*') && !request()->routeIs('projects.*') ? 'background:#1c1917;color:#fff;' : 'color:#78716c;' }}">Tasks</a>
                <a href="{{ route('settings') }}" class="px-3 py-2" style="color:#78716c;">Settings</a>
            </nav>

            <div class="flex items-center gap-2">
                <a href="{{ route('projects.create') }}" class="btn-ghost !py-2 hidden sm:inline-flex">+ Project</a>
                <a href="{{ route('tasks.create') }}" class="btn-primary !py-2">+ New task</a>
                @auth
                    <x-profile-popup :name="auth()->user()->name" :email="auth()->user()->email" />
                @else
                    <x-profile-popup name="Demo User" email="demo@jara.test" role="User" />
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-1">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 py-10" style="max-width:1200px;">
            @if (session('success'))
                <div class="mb-6 rounded-[10px] bg-white px-4 py-3 text-sm" style="border:1px solid #e8e6e5;box-shadow:rgba(0,0,0,.05) 0px 4px 16px 0px;">
                    <span class="font-medium" style="color:#0c0a09;">{{ session('success') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded-[10px] bg-white px-4 py-3 text-sm" style="border:1px solid #e8e6e5;">
                    <ul class="list-disc pl-5" style="color:#78716c;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="py-8 text-center text-xs" style="border-top:1px solid #e8e6e5;color:#a8a29e;">
        &copy; {{ date('Y') }} Jara — An Advanced To-Do List · Seline style
    </footer>
</body>
</html>
