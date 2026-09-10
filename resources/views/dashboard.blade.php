@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name', 'Tubes PPK Web'))

@section('content')
<div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8 py-16">
    <p class="text-[13px] text-[#78716c]">Masuk sebagai {{ auth()->user()->email }}</p>
    <h1 class="font-display text-[32px] leading-[1.25] tracking-[-0.8px] font-normal text-[#0c0a09] mt-2">
        Halo, {{ auth()->user()->name }} — ini <span class="hl">ruang kerjamu</span>
    </h1>
    <p class="mt-2 text-[16px] leading-[1.69] text-[#78716c]">Semua yang kamu butuhkan, dalam satu tempat yang tenang.</p>

    <div class="mt-8 grid gap-4 sm:grid-cols-2">
        @if (auth()->user()->role === 'admin')
            <div class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                <h2 class="font-display text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">Kelola user</h2>
                <p class="mt-1 text-[14px] text-[#78716c]">Tambah, ubah role, dan hapus user.</p>
                <a href="{{ route('users.index') }}" class="mt-4 inline-block rounded-full bg-[#3ba6f1] border border-[#3398e1] px-4 py-2 text-[14px] font-medium text-white hover:brightness-95">Buka kelola user</a>
            </div>
        @endif
        <div class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
            <h2 class="font-display text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">Pengaturan</h2>
            <p class="mt-1 text-[14px] text-[#78716c]">Kelola preferensi dan akunmu.</p>
            <a href="{{ route('settings') }}" class="mt-4 inline-block rounded-full border border-[#e8e6e5] px-4 py-2 text-[14px] text-[#0c0a09] hover:text-[#0c0a09]">Buka pengaturan</a>
        </div>
    </div>
</div>
@endsection
