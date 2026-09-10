@extends('layouts.app')

@section('title', 'Kelola User - ' . config('app.name', 'Tubes PPK Web'))

@section('content')
<div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-[32px] leading-[1.25] tracking-[-0.8px] font-normal text-[#0c0a09]">
                Kelola <span class="hl">user</span>
            </h1>
            <p class="mt-2 text-[14px] text-[#78716c]">Hanya admin · {{ $users->total() }} user terdaftar</p>
        </div>
        <a href="{{ route('users.create') }}" class="rounded-full bg-[#3ba6f1] border border-[#3398e1] px-4 py-2 text-[14px] font-medium text-white hover:brightness-95">
            + Tambah user
        </a>
    </div>

    @if (session('success'))
        <div class="mt-6 rounded-[10px] border border-[#e8e6e5] bg-white px-4 py-3 text-[14px] text-[#0c0a09] shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->has('user'))
        <div class="mt-6 rounded-[10px] border border-[#e8e6e5] bg-white px-4 py-3 text-[14px] text-[#0c0a09]">
            {{ $errors->first('user') }}
        </div>
    @endif

    <div class="mt-6 overflow-x-auto rounded-[10px] border border-[#e8e6e5] bg-white shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
        <table class="w-full text-[14px]">
            <thead>
                <tr class="border-b border-[#e8e6e5] text-left text-[#78716c]">
                    <th class="px-6 py-3 font-normal">Nama</th>
                    <th class="px-6 py-3 font-normal">Email</th>
                    <th class="px-6 py-3 font-normal">Role</th>
                    <th class="px-6 py-3 font-normal text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b border-[#e8e6e5] last:border-0">
                        <td class="px-6 py-3 text-[#0c0a09]">{{ $user->name }}</td>
                        <td class="px-6 py-3 text-[#78716c]">{{ $user->email }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center rounded-full border border-[#e8e6e5] px-2 py-0.5 text-[10px] uppercase tracking-wide text-[#78716c]">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('users.edit', $user) }}" class="text-[#3398e1] hover:underline mr-4">Edit</a>
                            <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('Hapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[#78716c] hover:text-[#0c0a09] hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-[#78716c]">Belum ada user.</td>
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
