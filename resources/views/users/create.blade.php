@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="mx-auto max-w-md px-4 py-10">
    <div class="rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] p-6">
        <h1 class="text-xl font-semibold mb-4">Tambah User</h1>

        <form method="POST" action="{{ route('users.store') }}" class="flex flex-col gap-4">
            @csrf

            <label class="flex flex-col gap-1 text-sm">
                <span>Nama</span>
                <input type="text" name="name" value="{{ old('name') }}" required class="rounded-lg border px-3 py-2">
                @error('name')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
            </label>

            <label class="flex flex-col gap-1 text-sm">
                <span>Email</span>
                <input type="email" name="email" value="{{ old('email') }}" required class="rounded-lg border px-3 py-2">
                @error('email')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
            </label>

            <label class="flex flex-col gap-1 text-sm">
                <span>Password</span>
                <input type="password" name="password" required class="rounded-lg border px-3 py-2">
                @error('password')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
            </label>

            <label class="flex flex-col gap-1 text-sm">
                <span>Konfirmasi Password</span>
                <input type="password" name="password_confirmation" required class="rounded-lg border px-3 py-2">
            </label>

            <label class="flex flex-col gap-1 text-sm">
                <span>Role</span>
                <select name="role" required class="rounded-lg border px-3 py-2">
                    <option value="user" @selected(old('role') === 'user')>user</option>
                    <option value="admin" @selected(old('role') === 'admin')>admin</option>
                </select>
                @error('role')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
            </label>

            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-[#F53003] px-5 py-2 text-sm text-white">Simpan</button>
                <a href="{{ route('users.index') }}" class="rounded-lg border px-5 py-2 text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
