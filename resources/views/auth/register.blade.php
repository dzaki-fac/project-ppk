@extends('layouts.app')

@section('title', 'Daftar - ' . config('app.name', 'Tubes PPK Web'))

@section('content')
<div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8 py-16">
    <div class="mx-auto max-w-md">
        <h1 class="font-display text-[32px] leading-[1.25] tracking-[-0.8px] font-normal text-[#0c0a09] text-center">
            Buat akun <span class="hl">barumu</span>
        </h1>
        <p class="mt-2 text-[14px] text-[#78716c] text-center">Daftar sebagai user, gratis.</p>

        <div class="mt-8 rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
            <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4">
                @csrf

                <label class="flex flex-col gap-1 text-[14px]">
                    <span class="text-[#0c0a09]">Nama</span>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama lengkap"
                        class="seline-input rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] placeholder-[#78716c]">
                    @error('name')
                        <span class="text-[13px] text-[#78716c]">{{ $message }}</span>
                    @enderror
                </label>

                <label class="flex flex-col gap-1 text-[14px]">
                    <span class="text-[#0c0a09]">Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                        class="seline-input rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] placeholder-[#78716c]">
                    @error('email')
                        <span class="text-[13px] text-[#78716c]">{{ $message }}</span>
                    @enderror
                </label>

                <label class="flex flex-col gap-1 text-[14px]">
                    <span class="text-[#0c0a09]">Password <span class="text-[#78716c]">(min. 8 karakter)</span></span>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="seline-input rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] placeholder-[#78716c]">
                    @error('password')
                        <span class="text-[13px] text-[#78716c]">{{ $message }}</span>
                    @enderror
                </label>

                <label class="flex flex-col gap-1 text-[14px]">
                    <span class="text-[#0c0a09]">Konfirmasi password</span>
                    <input type="password" name="password_confirmation" required placeholder="••••••••"
                        class="seline-input rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] placeholder-[#78716c]">
                </label>

                <button type="submit" class="rounded-full bg-[#3ba6f1] border border-[#3398e1] px-4 py-2 text-[14px] font-medium text-white hover:brightness-95">
                    Daftar
                </button>
            </form>
        </div>

        <p class="mt-4 text-[14px] text-[#78716c] text-center">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-[#3398e1] hover:underline">Masuk</a>
        </p>
    </div>
</div>
@endsection
