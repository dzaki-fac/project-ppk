{{-- Pop-up profil gaya Seline: kartu putih, hairline stone, aksi cyan --}}
@props([
    'name' => auth()->user()->name ?? 'User',
    'email' => auth()->user()->email ?? 'user@ppk.test',
    'avatar' => null,
    'role' => auth()->user()->role ?? null,
])

@php
    $initial = strtoupper(mb_substr($name, 0, 1));
@endphp

<div class="relative" id="profile-popup-root" data-profile-popup>
    <button
        type="button"
        id="profile-popup-trigger"
        aria-haspopup="dialog"
        aria-expanded="false"
        aria-controls="profile-popup-panel"
        class="flex items-center gap-2 rounded-full p-1 pr-2 hover:bg-white transition focus:outline-none"
    >
        @if($avatar)
            <img src="{{ $avatar }}" alt="Avatar {{ $name }}" class="h-8 w-8 rounded-full object-cover">
        @else
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#3ba6f1] text-white text-sm font-medium shrink-0">
                {{ $initial }}
            </span>
        @endif
        <span class="hidden sm:block max-w-[140px] truncate text-[14px] text-[#0c0a09]">{{ $name }}</span>
        <svg id="profile-chevron" class="h-4 w-4 text-[#a8a29e] transition-transform duration-200 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
        </svg>
    </button>

    <div id="profile-popup-backdrop" class="fixed inset-0 z-30 hidden" aria-hidden="true"></div>

    <div
        id="profile-popup-panel"
        role="dialog"
        aria-modal="true"
        class="absolute right-0 z-40 mt-2 hidden w-[300px] origin-top-right rounded-[10px] border border-[#e8e6e5] bg-white p-2 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]"
    >
        <div class="flex items-center gap-3 px-3 py-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#3ba6f1] text-white font-medium shrink-0">
                {{ $initial }}
            </span>
            <div class="min-w-0 flex-1">
                <p class="truncate text-[14px] font-medium text-[#0c0a09]">{{ $name }}</p>
                <p class="truncate text-[13px] text-[#78716c]">{{ $email }}</p>
                @if($role)
                    <span class="mt-1 inline-flex items-center rounded-full border border-[#e8e6e5] px-2 py-0.5 text-[10px] uppercase tracking-wide text-[#78716c]">
                        {{ $role }}
                    </span>
                @endif
            </div>
        </div>

        <div class="mx-2 border-t border-[#e8e6e5]"></div>

        <div class="p-1">
            <a
                href="{{ Route::has('settings') ? route('settings') : url('/settings') }}"
                class="flex items-center gap-3 rounded-[10px] bg-[#3ba6f1] border border-[#3398e1] px-3 py-2.5 text-[14px] font-medium text-white hover:brightness-95 transition"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87a6.002 6.002 0 011.316.814c.28.2.65.26.98.16l1.255-.39a1.125 1.125 0 011.37.558l1.296 2.247a1.125 1.125 0 01-.26 1.47l-1.02.82c-.293.235-.437.61-.38.974a6.02 6.02 0 010 1.69c-.057.363.087.738.38.974l1.02.82c.37.295.48.81.26 1.47l-1.296 2.247a1.125 1.125 0 01-1.37.558l-1.255-.39a1.1 1.1 0 00-.98.16 6.002 6.002 0 01-1.315.814 1.1 1.1 0 00-.646.87l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594a1.125 1.125 0 01-1.11-.94l-.213-1.281a1.1 1.1 0 00-.645-.87 6.002 6.002 0 01-1.316-.814 1.1 1.1 0 00-.98-.16l-1.255.39a1.125 1.125 0 01-1.37-.558L4.22 14.3a1.125 1.125 0 01.26-1.47l1.02-.82c.293-.235.437-.61.38-.974a6.02 6.02 0 010-1.69c.057-.363-.087-.738-.38-.974l-1.02-.82a1.125 1.125 0 01-.26-1.47l1.296-2.247a1.125 1.125 0 011.37-.558l1.255.39c.33.1.7.04.98-.16a6.002 6.002 0 011.316-.814 1.1 1.1 0 00.645-.87l.213-1.281z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Setting
            </a>
            <form method="POST" action="{{ Route::has('logout') ? route('logout') : url('/logout') }}">
                @csrf
                <button type="submit" class="mt-1 flex w-full items-center gap-3 rounded-[10px] px-3 py-2.5 text-[14px] text-[#78716c] hover:text-[#0c0a09] hover:bg-[#fafaf9] transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    const root = document.getElementById('profile-popup-root');
    if (!root) return;
    const trigger = document.getElementById('profile-popup-trigger');
    const panel = document.getElementById('profile-popup-panel');
    const backdrop = document.getElementById('profile-popup-backdrop');
    const chevron = document.getElementById('profile-chevron');
    let open = false;

    function setOpen(val) {
        open = val;
        trigger.setAttribute('aria-expanded', String(open));
        panel.classList.toggle('hidden', !open);
        backdrop.classList.toggle('hidden', !open);
        chevron.classList.toggle('rotate-180', open);
    }

    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        setOpen(!open);
    });
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
