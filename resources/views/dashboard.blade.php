@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name', 'Tubes PPK Web'))

@section('content')
<div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
        Halo, {{ auth()->user()->name }}!
    </h1>
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">
        Login sebagai {{ auth()->user()->email }} (role: {{ auth()->user()->role }})
    </p>

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        @if (auth()->user()->role === 'admin')
            <a href="{{ route('users.index') }}" class="rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] p-6 hover:border-[#F53003]">
                <h2 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">User Management</h2>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Kelola user + role admin/user.</p>
            </a>
        @endif
        <a href="{{ route('settings') }}" class="rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] p-6 hover:border-[#F53003]">
            <h2 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Settings</h2>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Kelola preferensi akun.</p>
        </a>
    </div>
</div>
@endsection
