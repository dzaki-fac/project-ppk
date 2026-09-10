@extends('layouts.app')

@section('title', 'Pengaturan - Jara')

@section('content')
<div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8 py-16">
    @php
        $user = auth()->user();
        $initial = strtoupper(mb_substr($user->name ?? 'U', 0, 1));
    @endphp

    {{-- Header: 32px Roobert + 1 highlight, sub 16px --}}
    <p class="text-[13px] text-[#78716c]">Masuk sebagai {{ $user->email }}</p>
    <h1 class="font-display mt-2 text-[32px] font-normal leading-[1.25] tracking-[-0.8px] text-[#0c0a09]">
        Pengaturan yang <span class="hl">tenang</span>
    </h1>
    <p class="mt-2 max-w-[620px] text-[16px] leading-[1.69] text-[#78716c]">
        Kelola profil, preferensi, dan keamanan akun Jara-mu. Perubahan tersimpan otomatis per bagian.
    </p>

    <div class="mt-8 grid gap-4 lg:grid-cols-[260px_1fr]">
        {{-- Sidebar: flat card, active = soot pill --}}
        <aside class="h-fit rounded-[10px] border border-[#e8e6e5] bg-white p-2 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
            <nav class="flex flex-col gap-1 text-[14px]">
                <a href="#profil" class="flex items-center gap-2 rounded-full bg-[#1c1917] px-4 py-2 font-medium text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 19.5a7.5 7.5 0 0115 0V21a.75.75 0 01-.75.75h-13.5A.75.75 0 014.5 21v-1.5z"/></svg>
                    Profil
                </a>
                <a href="#preferensi" class="flex items-center gap-2 rounded-full border border-transparent px-4 py-2 text-[#0c0a09] hover:border-[#e8e6e5]">
                    <svg class="h-4 w-4 text-[#a8a29e]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87a6.002 6.002 0 011.316.814c.28.2.65.26.98.16l1.255-.39a1.125 1.125 0 011.37.558l1.296 2.247a1.125 1.125 0 01-.26 1.47l-1.02.82c-.293.235-.437.61-.38.974a6.02 6.02 0 010 1.69c-.057.363.087.738.38.974l1.02.82c.37.295.48.81.26 1.47l-1.296 2.247a1.125 1.125 0 01-1.37.558l-1.255-.39a1.1 1.1 0 00-.98.16 6.002 6.002 0 01-1.315.814 1.1 1.1 0 00-.646.87l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594a1.125 1.125 0 01-1.11-.94l-.213-1.281a1.1 1.1 0 00-.645-.87 6.002 6.002 0 01-1.316-.814 1.1 1.1 0 00-.98-.16l-1.255.39a1.125 1.125 0 01-1.37-.558L4.22 14.3a1.125 1.125 0 01.26-1.47l1.02-.82c.293-.235.437-.61.38-.974a6.02 6.02 0 010-1.69c.057-.363-.087-.738-.38-.974l-1.02-.82a1.125 1.125 0 01-.26-1.47l1.296-2.247a1.125 1.125 0 011.37-.558l1.255.39c.33.1.7.04.98-.16a6.002 6.002 0 011.316-.814 1.1 1.1 0 00.645-.87l.213-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Preferensi
                </a>
                <a href="#keamanan" class="flex items-center gap-2 rounded-full border border-transparent px-4 py-2 text-[#0c0a09] hover:border-[#e8e6e5]">
                    <svg class="h-4 w-4 text-[#a8a29e]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    Keamanan
                </a>
                <a href="#notifikasi" class="flex items-center gap-2 rounded-full border border-transparent px-4 py-2 text-[#0c0a09] hover:border-[#e8e6e5]">
                    <svg class="h-4 w-4 text-[#a8a29e]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    Notifikasi
                </a>
            </nav>

            <div class="mx-2 my-2 border-t border-[#e8e6e5]"></div>

            <div class="flex items-center gap-3 px-3 py-2">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#3ba6f1] text-[13px] font-medium text-white">{{ $initial }}</span>
                <div class="min-w-0">
                    <p class="truncate text-[14px] font-medium text-[#0c0a09]">{{ $user->name }}</p>
                    <p class="truncate text-[12px] text-[#78716c]">{{ ucfirst($user->role) }}</p>
                </div>
            </div>
        </aside>

        {{-- Content --}}
        <div class="space-y-4">
            {{-- Profil --}}
            <section id="profil" class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="font-display text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">Informasi akun</h2>
                        <p class="mt-1 text-[14px] text-[#78716c]">Diakses via pop-up profil → Setting. Field email &amp; role hanya-baca sesuai SRS.</p>
                    </div>
                    <span class="inline-flex shrink-0 items-center rounded-full border border-[#e8e6e5] px-2.5 py-0.5 text-[12px] text-[#78716c]">{{ $user->role }}</span>
                </div>

                <div class="mt-4 flex items-center gap-3 rounded-[10px] border border-[#e8e6e5] p-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#3ba6f1] font-medium text-white">{{ $initial }}</span>
                    <div class="min-w-0">
                        <p class="truncate text-[14px] font-medium text-[#0c0a09]">{{ $user->name }}</p>
                        <p class="truncate text-[13px] text-[#78716c]">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <label class="flex flex-col gap-1 text-[14px]">
                        <span class="text-[13px] text-[#78716c]">Nama</span>
                        <input value="{{ $user->name }}" class="seline-input rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] placeholder-[#78716c]">
                    </label>
                    <label class="flex flex-col gap-1 text-[14px]">
                        <span class="text-[13px] text-[#78716c]">Email</span>
                        <input value="{{ $user->email }}" readonly class="rounded-[6px] border border-[#e8e6e5] bg-[#fafaf9] px-3 py-2 text-[14px] text-[#78716c]">
                    </label>
                </div>

                <div class="mt-6 flex flex-wrap gap-2">
                    <button class="rounded-full border border-[#3398e1] bg-[#3ba6f1] px-4 py-2 text-[14px] font-medium text-white hover:brightness-95">Simpan</button>
                    <a href="{{ url('/') }}" class="rounded-full border border-[#e8e6e5] bg-transparent px-4 py-2 text-[14px] text-[#0c0a09]">Kembali</a>
                </div>
            </section>

            {{-- Preferensi --}}
            <section id="preferensi" class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                <h2 class="font-display text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">Preferensi</h2>
                <p class="mt-1 text-[14px] text-[#78716c]">Tampilan &amp; bahasa. Bersifat lokal, tidak mengubah data SRS.</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <label class="flex flex-col gap-1 text-[14px]">
                        <span class="text-[13px] text-[#78716c]">Tema</span>
                        <select class="seline-input rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09]">
                            <option>Light</option>
                            <option>Dark</option>
                            <option>System</option>
                        </select>
                    </label>
                    <label class="flex flex-col gap-1 text-[14px]">
                        <span class="text-[13px] text-[#78716c]">Bahasa</span>
                        <select class="seline-input rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09]">
                            <option>Indonesia</option>
                            <option>English</option>
                        </select>
                    </label>
                </div>
            </section>

            <div class="grid gap-4 sm:grid-cols-2">
                {{-- Keamanan --}}
                <section id="keamanan" class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                    <h2 class="font-display text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">Keamanan</h2>
                    <p class="mt-1 text-[14px] text-[#78716c]">Ganti kata sandi secara berkala untuk menjaga project tim.</p>
                    <button class="mt-4 rounded-full border border-[#e8e6e5] px-4 py-2 text-[14px] text-[#0c0a09]">Ubah kata sandi</button>
                </section>

                {{-- Notifikasi --}}
                <section id="notifikasi" class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                    <h2 class="font-display text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">Notifikasi</h2>
                    <p class="mt-1 text-[14px] text-[#78716c]">Pilih kabar deadline &amp; undangan project yang ingin diterima.</p>
                    <div class="mt-4 space-y-2 text-[14px] text-[#0c0a09]">
                        <label class="flex items-center justify-between gap-3 rounded-[6px] border border-[#e8e6e5] px-3 py-2">
                            <span>Deadline mendekat</span>
                            <input type="checkbox" checked class="h-4 w-4 accent-[#3ba6f1]">
                        </label>
                        <label class="flex items-center justify-between gap-3 rounded-[6px] border border-[#e8e6e5] px-3 py-2">
                            <span>Undangan project</span>
                            <input type="checkbox" checked class="h-4 w-4 accent-[#3ba6f1]">
                        </label>
                    </div>
                </section>
            </div>

            {{-- Zona berbahaya: tanpa warna baru, tetap stone --}}
            <section class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                <h3 class="text-[14px] font-medium text-[#0c0a09]">Zona berbahaya</h3>
                <p class="mt-1 text-[13px] text-[#78716c]">Hapus akun secara permanen. Project yang kamu miliki ikut terhapus via cascade.</p>
                <button class="mt-3 rounded-full border border-[#e8e6e5] px-4 py-2 text-[14px] text-[#0c0a09] hover:border-[#d6d3d1]">Hapus akun</button>
            </section>
        </div>
    </div>
</div>
@endsection
