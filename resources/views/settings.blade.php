@extends('layouts.app')

@section('title', 'Setting - ' . config('app.name', 'Tubes PPK Web'))

@section('content')
<div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] flex items-center gap-2">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#F53003]/10 text-[#F53003]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87a6.002 6.002 0 011.316.814c.28.2.65.26.98.16l1.255-.39a1.125 1.125 0 011.37.558l1.296 2.247a1.125 1.125 0 01-.26 1.47l-1.02.82c-.293.235-.437.61-.38.974a6.02 6.02 0 010 1.69c-.057.363.087.738.38.974l1.02.82c.37.295.48.81.26 1.47l-1.296 2.247a1.125 1.125 0 01-1.37.558l-1.255-.39a1.1 1.1 0 00-.98.16 6.002 6.002 0 01-1.315.814 1.1 1.1 0 00-.646.87l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594a1.125 1.125 0 01-1.11-.94l-.213-1.281a1.1 1.1 0 00-.645-.87 6.002 6.002 0 01-1.316-.814 1.1 1.1 0 00-.98-.16l-1.255.39a1.125 1.125 0 01-1.37-.558L4.22 14.3a1.125 1.125 0 01.26-1.47l1.02-.82c.293-.235.437-.61.38-.974a6.02 6.02 0 010-1.69c.057-.363-.087-.738-.38-.974l-1.02-.82a1.125 1.125 0 01-.26-1.47l1.296-2.247a1.125 1.125 0 011.37-.558l1.255.39c.33.1.7.04.98-.16a6.002 6.002 0 011.316-.814 1.1 1.1 0 00.645-.87l.213-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </span>
            Setting
        </h1>
        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">Kelola preferensi akun, notifikasi, dan keamanan.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-[260px_1fr]">
        {{-- Sidebar --}}
        <aside class="rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] p-2 h-fit">
            <nav class="flex flex-col gap-1 text-sm">
                <a href="#" class="rounded-lg bg-[#F53003] text-white px-3 py-2.5 font-medium flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 19.5a7.5 7.5 0 0115 0V21a.75.75 0 01-.75.75h-13.5A.75.75 0 014.5 21v-1.5z"/></svg>
                    Profil
                </a>
                <a href="#" class="rounded-lg px-3 py-2.5 text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f5f5f3] dark:hover:bg-[#232321] flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    Notifikasi
                </a>
                <a href="#" class="rounded-lg px-3 py-2.5 text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f5f5f3] dark:hover:bg-[#232321] flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    Keamanan
                </a>
                <a href="#" class="rounded-lg px-3 py-2.5 text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f5f5f3] dark:hover:bg-[#232321] flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87a6.002 6.002 0 011.316.814c.28.2.65.26.98.16l1.255-.39a1.125 1.125 0 011.37.558l1.296 2.247a1.125 1.125 0 01-.26 1.47l-1.02.82c-.293.235-.437.61-.38.974a6.02 6.02 0 010 1.69c-.057.363.087.738.38.974l1.02.82c.37.295.48.81.26 1.47l-1.296 2.247a1.125 1.125 0 01-1.37.558l-1.255-.39a1.1 1.1 0 00-.98.16 6.002 6.002 0 01-1.315.814 1.1 1.1 0 00-.646.87l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594a1.125 1.125 0 01-1.11-.94l-.213-1.281a1.1 1.1 0 00-.645-.87 6.002 6.002 0 01-1.316-.814 1.1 1.1 0 00-.98-.16l-1.255.39a1.125 1.125 0 01-1.37-.558L4.22 14.3a1.125 1.125 0 01.26-1.47l1.02-.82c.293-.235.437-.61.38-.974a6.02 6.02 0 010-1.69c.057-.363-.087-.738-.38-.974l-1.02-.82a1.125 1.125 0 01-.26-1.47l1.296-2.247a1.125 1.125 0 011.37-.558l1.255.39c.33.1.7.04.98-.16a6.002 6.002 0 011.316-.814 1.1 1.1 0 00.645-.87l.213-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Preferensi
                </a>
            </nav>
        </aside>

        {{-- Content --}}
        <div class="space-y-6">
            <div class="rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] p-6">
                <h2 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Informasi Akun</h2>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mb-4">Diakses via pop-up profil → Setting.</p>
                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="flex flex-col gap-1 text-sm">
                        <span class="text-[#706f6c] dark:text-[#A1A09A]">Username</span>
                        <input value="{{ auth()->user()->username ?? 'demo_user' }}" class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-3 py-2 text-[#1b1b18] dark:text-[#EDEDEC]" readonly>
                    </label>
                    <label class="flex flex-col gap-1 text-sm">
                        <span class="text-[#706f6c] dark:text-[#A1A09A]">Email</span>
                        <input value="{{ auth()->user()->email ?? 'demo@ppk.test' }}" class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-3 py-2 text-[#1b1b18] dark:text-[#EDEDEC]" readonly>
                    </label>
                    <label class="flex flex-col gap-1 text-sm">
                        <span class="text-[#706f6c] dark:text-[#A1A09A]">Tema</span>
                        <select class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-3 py-2 text-[#1b1b18] dark:text-[#EDEDEC]">
                            <option>Light</option>
                            <option>Dark</option>
                            <option>System</option>
                        </select>
                    </label>
                    <label class="flex flex-col gap-1 text-sm">
                        <span class="text-[#706f6c] dark:text-[#A1A09A]">Bahasa</span>
                        <select class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-3 py-2 text-[#1b1b18] dark:text-[#EDEDEC]">
                            <option>Indonesia</option>
                            <option>English</option>
                        </select>
                    </label>
                </div>
                <div class="mt-6 flex gap-3">
                    <button class="rounded-lg bg-[#F53003] px-5 py-2 text-sm font-medium text-white hover:bg-[#d92a03]">Simpan</button>
                    <a href="{{ url('/') }}" class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] px-5 py-2 text-sm hover:bg-[#f5f5f3] dark:hover:bg-[#232321]">Kembali</a>
                </div>
            </div>

            <div class="rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] p-6">
                <h3 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Zona Berbahaya</h3>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mb-3">Hapus akun secara permanen.</p>
                <button class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-100">Hapus Akun</button>
            </div>
        </div>
    </div>
</div>
@endsection
