@extends('layouts.app')

@section('title', 'Login - ' . config('app.name', 'Tubes PPK Web'))

@section('content')
<div class="mx-auto max-w-md px-4 py-10">
    <div class="rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] p-6">
        <h1 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Login</h1>
        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Masuk untuk lanjut ke dashboard.</p>

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
            @csrf

            <label class="flex flex-col gap-1 text-sm">
                <span class="text-[#706f6c] dark:text-[#A1A09A]">Email</span>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-3 py-2 text-[#1b1b18] dark:text-[#EDEDEC]">
                @error('email')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-1 text-sm">
                <span class="text-[#706f6c] dark:text-[#A1A09A]">Password</span>
                <input type="password" name="password" required
                    class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-3 py-2 text-[#1b1b18] dark:text-[#EDEDEC]">
                @error('password')
                    <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex items-center gap-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                <input type="checkbox" name="remember" value="1" class="rounded">
                Ingat saya
            </label>

            <button type="submit" class="rounded-lg bg-[#F53003] px-5 py-2 text-sm font-medium text-white hover:bg-[#d92a03]">
                Login
            </button>
        </form>

        <p class="mt-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-[#F53003] hover:underline">Register</a>
        </p>
    </div>
</div>
@endsection
