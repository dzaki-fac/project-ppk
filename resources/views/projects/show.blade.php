@extends('layouts.app')

@section('title', $project->name . ' - ' . config('app.name', 'Jara'))

@section('content')
<div class="bg-[#fafaf9]">
    <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8 py-12">
        <a href="{{ route('projects.index') }}" class="text-[14px] text-[#78716c] hover:text-[#0c0a09]">&larr; Kembali ke daftar project</a>

        @if (session('status'))
            <div class="mt-4 rounded-[10px] border border-[#e8e6e5] bg-white px-4 py-3 text-sm text-[#0c0a09] shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 rounded-[10px] border border-[#e8e6e5] bg-white px-4 py-3 text-sm text-red-600 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Detail project --}}
        <div class="mt-6 rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-[32px] leading-[1.25] tracking-[-0.8px] font-normal text-[#0c0a09]" style="font-family:'Inter Tight','Roobert',ui-sans-serif,system-ui,sans-serif;">
                        {{ $project->name }}
                    </h1>
                    <p class="mt-2 text-[14px] leading-[1.64] text-[#78716c]">
                        {{ $project->description ?: 'Tanpa deskripsi.' }}
                    </p>
                    <p class="mt-3 text-[12px] text-[#a8a29e]">
                        Owner: {{ $project->owner->name ?? '-' }} ({{ $project->owner->email ?? '-' }})
                        &middot; {{ $project->members->count() }} member
                        &middot; dibuat {{ $project->created_at->format('d M Y') }}
                    </p>
                </div>
                @if ($isOwner)
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('projects.edit', $project) }}" class="rounded-full border border-[#e8e6e5] px-4 py-2 text-sm text-[#0c0a09] hover:bg-[#fafaf9] transition">Edit</a>
                        <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Hapus project ini?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-full border border-[#e8e6e5] px-4 py-2 text-sm text-[#0c0a09] hover:bg-[#fafaf9] transition">Hapus</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_380px]">
            {{-- Daftar member --}}
            <div class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]" style="font-family:'Inter Tight','Roobert',ui-sans-serif,system-ui,sans-serif;">
                        Member ({{ $project->members->count() }})
                    </h2>
                    <a href="{{ route('projects.members.index', $project) }}" class="text-[13px] text-[#3398e1] hover:underline">Kelola member</a>
                </div>

                @if ($project->members->isEmpty())
                    <p class="mt-4 text-[14px] leading-[1.64] text-[#78716c]">Belum ada member. Owner dapat menambahkan user lain via email.</p>
                @else
                    <ul class="mt-4 divide-y divide-[#e8e6e5]">
                        @foreach ($project->members as $member)
                            <li class="flex items-center justify-between gap-3 py-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#3ba6f1] text-white text-sm font-semibold">
                                        {{ strtoupper(mb_substr($member->name, 0, 1)) }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate text-[14px] font-medium text-[#0c0a09]">{{ $member->name }}</p>
                                        <p class="truncate text-[12px] text-[#78716c]">{{ $member->email }}</p>
                                    </div>
                                </div>
                                @if ($isOwner)
                                    <form method="POST" action="{{ route('projects.members.destroy', [$project, $member]) }}" onsubmit="return confirm('Hapus {{ addslashes($member->name) }} dari project?');" class="shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full border border-[#e8e6e5] px-4 py-2 text-[13px] text-[#0c0a09] hover:bg-[#fafaf9] transition">Hapus</button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Tambah member + info task (tanpa menyentuh CRUD task milik Programmer 3) --}}
            <div class="flex flex-col gap-6">
                @if ($isOwner)
                    <div class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                        <h3 class="text-[16px] font-medium text-[#0c0a09]">Tambah member</h3>
                        <p class="mt-1 text-[13px] text-[#78716c]">Masukkan email user yang sudah terdaftar.</p>
                        <form method="POST" action="{{ route('projects.members.store', $project) }}" class="mt-4 flex flex-col gap-3">
                            @csrf
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.test"
                                class="rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] placeholder-[#78716c] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
                            <button type="submit" class="rounded-full bg-[#3ba6f1] border border-[#3398e1] text-white text-sm font-medium px-4 py-2 hover:opacity-90 transition">Tambah</button>
                        </form>
                    </div>
                @endif

                <div class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                    <h3 class="text-[16px] font-medium text-[#0c0a09]">Tasks</h3>
                    <p class="mt-1 text-[13px] leading-[1.64] text-[#78716c]">
                        Task management dikerjakan Programmer 3 dan akan tampil di sini tanpa mengubah fitur project ini.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
