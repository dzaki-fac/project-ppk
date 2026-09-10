{{-- resources/views/components/profile-popup.blade.php : Pop-up Profil dengan Tombol Setting --}}
@props([
    'name' => auth()->user()->name ?? (auth()->user()->username ?? 'User Demo'),
    'email' => auth()->user()->email ?? 'user@demo.test',
    'avatar' => null,
    'role' => null,
])

@php
    // Fallback avatar: inisial
    $initial = strtoupper(mb_substr($name, 0, 1));
    // Coba ambil role dari DB jika ada, fallback ke props
    $displayRole = $role ?? (auth()->user()->user_role ?? null);
@endphp

<div class="relative" id="profile-popup-root" data-profile-popup>
    {{-- Trigger Button --}}
    <button
        type="button"
        id="profile-popup-trigger"
        aria-haspopup="dialog"
        aria-expanded="false"
        aria-controls="profile-popup-panel"
        class="flex items-center gap-3 rounded-full border border-transparent p-1 pr-3 hover:border-[#e3e3e0] dark:hover:border-[#3E3E3A] hover:bg-white dark:hover:bg-[#161615] transition focus:outline-none focus-visible:ring-2 focus-visible:ring-[#F53003]/40"
    >
        @if($avatar)
            <img src="{{ $avatar }}" alt="Avatar {{ $name }}" class="h-8 w-8 rounded-full object-cover border border-[#e3e3e0] dark:border-[#3E3E3A]">
        @else
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#F53003] text-white text-sm font-semibold shrink-0">
                {{ $initial }}
            </span>
        @endif
        <span class="hidden sm:flex flex-col items-start leading-none">
            <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $name }}</span>
            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A] max-w-[140px] truncate">{{ $email }}</span>
        </span>
        <svg id="profile-chevron" class="h-4 w-4 text-[#706f6c] dark:text-[#A1A09A] transition-transform duration-200 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
        </svg>
    </button>

    {{-- Backdrop untuk mobile (klik luar) --}}
    <div id="profile-popup-backdrop" class="fixed inset-0 z-30 hidden bg-black/10 backdrop-blur-[1px] sm:bg-transparent sm:backdrop-blur-none" aria-hidden="true"></div>

    {{-- Panel Pop-up --}}
    <div
        id="profile-popup-panel"
        role="dialog"
        aria-modal="true"
        aria-labelledby="profile-popup-name"
        class="absolute right-0 z-40 mt-3 hidden w-[320px] origin-top-right overflow-hidden rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] shadow-[0_8px_30px_rgba(0,0,0,0.12)] dark:shadow-[0_8px_30px_rgba(0,0,0,0.5)]"
    >
        {{-- Header Profil --}}
        <div class="flex items-center gap-4 p-5 pb-4">
            @if($avatar)
                <img src="{{ $avatar }}" alt="Avatar {{ $name }}" class="h-12 w-12 rounded-full object-cover border border-[#e3e3e0] dark:border-[#3E3E3A]">
            @else
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-[#F53003] text-white text-lg font-bold shrink-0">
                    {{ $initial }}
                </span>
            @endif
            <div class="min-w-0 flex-1">
                <p id="profile-popup-name" class="truncate text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $name }}</p>
                <p class="truncate text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $email }}</p>
                @if($displayRole)
                    <span class="mt-1 inline-flex items-center rounded-full bg-[#FDFDFC] dark:bg-[#1e1e1c] border border-[#e3e3e0] dark:border-[#3E3E3A] px-2 py-0.5 text-[10px] font-medium tracking-wide text-[#706f6c] dark:text-[#A1A09A] uppercase">
                        {{ is_numeric($displayRole) ? 'Role #' . $displayRole : $displayRole }}
                    </span>
                @endif
            </div>
            <button type="button" id="profile-popup-close" class="flex h-7 w-7 items-center justify-center rounded-full hover:bg-[#f5f5f3] dark:hover:bg-[#232321] text-[#706f6c] dark:text-[#A1A09A] transition" aria-label="Tutup pop-up profil">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                </svg>
            </button>
        </div>

        <div class="mx-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]"></div>

        {{-- Menu Utama --}}
        <div class="p-2">
            <ul class="flex flex-col gap-1" role="menu">
                <li role="none">
                    <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}" role="menuitem" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f5f5f3] dark:hover:bg-[#232321] transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#eef2ff] dark:bg-[#1e1e3a] text-[#4338ca]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 19.5a7.5 7.5 0 0115 0V21a.75.75 0 01-.75.75h-13.5A.75.75 0 014.5 21v-1.5z"/></svg>
                        </span>
                        <span class="flex flex-col items-start">
                            <span class="font-medium leading-none">Profil Saya</span>
                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A] leading-none mt-1">Lihat & edit profil</span>
                        </span>
                        <svg class="ml-auto h-4 w-4 text-[#A1A09A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                </li>

                {{-- === TOMBOL SETTING (BARU) === --}}
                <li role="none">
                    <a
                        href="{{ Route::has('settings') ? route('settings') : (Route::has('settings.index') ? route('settings.index') : url('/settings')) }}"
                        role="menuitem"
                        class="group flex items-center gap-3 rounded-lg border border-transparent bg-[#F53003]/[0.06] dark:bg-[#F53003]/[0.08] hover:bg-[#F53003] hover:text-white dark:hover:bg-[#F53003] px-3 py-2.5 text-sm text-[#1b1b18] dark:text-[#EDEDEC] transition"
                    >
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] group-hover:bg-white/20 group-hover:border-white/20 group-hover:text-white text-[#F53003] dark:text-[#FF4433] transition">
                            {{-- Gear Icon --}}
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87a6.002 6.002 0 011.316.814c.28.2.65.26.98.16l1.255-.39a1.125 1.125 0 011.37.558l1.296 2.247a1.125 1.125 0 01-.26 1.47l-1.02.82c-.293.235-.437.61-.38.974a6.02 6.02 0 010 1.69c-.057.363.087.738.38.974l1.02.82c.37.295.48.81.26 1.47l-1.296 2.247a1.125 1.125 0 01-1.37.558l-1.255-.39a1.1 1.1 0 00-.98.16 6.002 6.002 0 01-1.315.814 1.1 1.1 0 00-.646.87l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594a1.125 1.125 0 01-1.11-.94l-.213-1.281a1.1 1.1 0 00-.645-.87 6.002 6.002 0 01-1.316-.814 1.1 1.1 0 00-.98-.16l-1.255.39a1.125 1.125 0 01-1.37-.558L4.22 14.3a1.125 1.125 0 01.26-1.47l1.02-.82c.293-.235.437-.61.38-.974a6.02 6.02 0 010-1.69c.057-.363-.087-.738-.38-.974l-1.02-.82a1.125 1.125 0 01-.26-1.47l1.296-2.247a1.125 1.125 0 011.37-.558l1.255.39c.33.1.7.04.98-.16a6.002 6.002 0 011.316-.814 1.1 1.1 0 00.645-.87l.213-1.281z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        <span class="flex flex-col items-start">
                            <span class="font-semibold leading-none">Setting</span>
                            <span class="text-xs leading-none mt-1 opacity-70 group-hover:text-white/80 dark:text-[#A1A09A] group-hover:dark:text-white/80">Kelola preferensi & akun</span>
                        </span>
                        <span class="ml-auto flex items-center gap-1">
                            <span class="hidden sm:inline-flex items-center rounded-full bg-[#F53003] group-hover:bg-white px-2 py-0.5 text-[10px] font-bold tracking-widest text-white group-hover:text-[#F53003] transition">BARU</span>
                            <svg class="h-4 w-4 opacity-60 group-hover:opacity-100 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </span>
                    </a>
                </li>

                <li role="none">
                    <a href="#" role="menuitem" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f5f5f3] dark:hover:bg-[#232321] transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#fef3c7] dark:bg-[#2a2000] text-[#d97706]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        </span>
                        <span class="font-medium">Notifikasi</span>
                        <span class="ml-auto h-2 w-2 rounded-full bg-[#F53003] animate-pulse"></span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="mx-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]"></div>

        {{-- Footer Actions --}}
        <div class="p-2 pb-3">
            <ul class="flex flex-col gap-1">
                <li>
                    <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-[#706f6c] dark:text-[#A1A09A] hover:bg-[#f5f5f3] dark:hover:bg-[#232321] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Bantuan
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ Route::has('logout') ? route('logout') : url('/logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm text-[#F53003] dark:text-[#FF4433] hover:bg-[#fff1f0] dark:hover:bg-[#2a0a06] transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                            Keluar
                        </button>
                    </form>
                </li>
            </ul>
            <p class="mt-2 px-3 text-[10px] leading-tight text-[#A1A09A]">Masuk sebagai <span class="font-medium text-[#706f6c] dark:text-[#A1A09A]">{{ $email }}</span></p>
        </div>
    </div>
</div>

{{-- Script Toggle (vanilla JS, tanpa Alpine) --}}
<script>
(function() {
    const root = document.getElementById('profile-popup-root');
    if (!root) return;
    const trigger = document.getElementById('profile-popup-trigger');
    const panel = document.getElementById('profile-popup-panel');
    const backdrop = document.getElementById('profile-popup-backdrop');
    const closeBtn = document.getElementById('profile-popup-close');
    const chevron = document.getElementById('profile-chevron');
    let open = false;

    function setOpen(val) {
        open = val;
        trigger.setAttribute('aria-expanded', String(open));
        panel.classList.toggle('hidden', !open);
        backdrop.classList.toggle('hidden', !open);
        chevron.classList.toggle('rotate-180', open);
        if (open) {
            // focus trap simpel: focus ke close button
            setTimeout(() => closeBtn?.focus(), 10);
            document.body.style.overflow = 'hidden';
            if (window.innerWidth >= 640) document.body.style.overflow = '';
        } else {
            document.body.style.overflow = '';
            trigger.focus();
        }
    }

    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        setOpen(!open);
    });
    closeBtn?.addEventListener('click', () => setOpen(false));
    backdrop?.addEventListener('click', () => setOpen(false));

    document.addEventListener('click', (e) => {
        if (!open) return;
        if (!root.contains(e.target)) setOpen(false);
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && open) setOpen(false);
    });
})();
</script>
