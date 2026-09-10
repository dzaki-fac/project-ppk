@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="mx-auto max-w-[480px]">
    <h1 class="font-display text-[32px] leading-[1.25] font-normal text-[#0c0a09]">Selamat datang <span class="text-[#3398e1] bg-[#c1e1f7] px-2 py-0.5 rounded">kembali</span></h1>
    <p class="mt-2 text-[14px] leading-[1.64] text-[#78716c]">Masuk untuk mengelola project dan tugas tim Anda.</p>

    <div class="mt-6 bg-white border border-[#e8e6e5] rounded-[10px] p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[13px] font-medium mb-1.5" for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com" class="w-full bg-white border border-[#d6d3d1] rounded-[6px] px-3 py-2 text-[14px] placeholder:text-[#78716c] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
            </div>
            <div>
                <label class="block text-[13px] font-medium mb-1.5" for="password">Password</label>
                <input id="password" name="password" type="password" required placeholder="••••••••" class="w-full bg-white border border-[#d6d3d1] rounded-[6px] px-3 py-2 text-[14px] placeholder:text-[#78716c] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
            </div>
            <button type="submit" class="w-full px-4 py-2 text-[14px] font-medium text-white bg-[#3ba6f1] border border-[#3398e1] rounded-full hover:opacity-90">Masuk</button>
        </form>
        <p class="mt-4 text-center text-[14px] text-[#78716c]">Belum punya akun? <a href="{{ route('register') }}" class="text-[#3398e1] font-medium">Daftar</a></p>
    </div>
</div>
@endsection
