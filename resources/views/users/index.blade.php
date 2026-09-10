@extends('layouts.app')

@section('title', 'Kelola User - ' . config('app.name', 'Tubes PPK Web'))

@section('content')
<div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Kelola User</h1>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Hanya admin. Total: {{ $users->total() }}</p>
        </div>
        <a href="{{ route('users.create') }}" class="rounded-lg bg-[#F53003] px-4 py-2 text-sm font-medium text-white hover:bg-[#d92a03]">
            + Tambah User
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->has('user'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-600">
            {{ $errors->first('user') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615]">
        <table class="w-full text-sm">
            <thead class="text-left text-[#706f6c] dark:text-[#A1A09A] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] last:border-0">
                        <td class="px-4 py-3 text-[#1b1b18] dark:text-[#EDEDEC]">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-[#706f6c] dark:text-[#A1A09A]">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full border border-[#e3e3e0] dark:border-[#3E3E3A] px-2 py-0.5 text-xs uppercase">{{ $user->role }}</span>
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('users.edit', $user) }}" class="text-[#F53003] hover:underline mr-3">Edit</a>
                            <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('Hapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-[#706f6c]">Belum ada user.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
