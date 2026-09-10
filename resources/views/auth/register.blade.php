@extends('layouts.app')

@section('title', 'Register - ' . config('app.name', 'Tubes PPK Web'))

@section('content')
<div class="mx-auto max-w-md px-4 py-10">
    <div class="rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] p-6">
        <h1 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Register</h1>
        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Buat akun baru sebagai user.</p>

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4">
            @csrf

            <label class="flex flex-col gap-1 text-sm">
                <span class="text-[#706f6c] dark:text-[#A1A09A]">Nama</span>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-3 py-2 text-[#1b1b18] dark:text-[#EDEDEC]">
                @error('name')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-1 text-sm">
                <span class="text-[#706f6c] dark:text-[#A1A09A]">Email</span>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-3 py-2 text-[#1b1b18] dark:text-[#EDEDEC]">
                @error('email')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-1 text-sm">
                <span class="text-[#706f6c] dark:text-[#A1A09A]">Password (min. 8 karakter)</span>
                <input type="password" name="password" required
                    class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-3 py-2 text-[#1b1b18] dark:text-[#EDEDEC]">
                @error('password')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-1 text-sm">
                <span class="text-[#706f6c] dark:text-[#A1A09A]">Konfirmasi Password</span>
                <input type="password" name="password_confirmation" required
                    class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-3 py-2 text-[#1b1b18] dark:text-[#EDEDEC]">
            </label>

            <button type="submit" class="rounded-lg bg-[#1b1b18] dark:bg-[#eeeeec] dark:text-[#1C1C1A] px-5 py-2 text-sm font-medium text-white hover:bg-black">
                Register
            </button>
        </form>

        <p class="mt-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-[#F53003] hover:underline">Login</a>
        </p>
    </div>
</div>
@endsection
