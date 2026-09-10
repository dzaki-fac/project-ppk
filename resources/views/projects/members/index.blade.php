@extends('layouts.app')

@section('title', 'Member - ' . $project->name)

@section('content')
<div class="bg-[#fafaf9]">
    <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8 py-12">
        <a href="{{ route('projects.show', $project) }}" class="text-[14px] text-[#78716c] hover:text-[#0c0a09]">&larr; Kembali ke {{ $project->name }}</a>

        <h1 class="mt-4 text-[32px] leading-[1.25] tracking-[-0.8px] font-normal text-[#0c0a09]" style="font-family:'Inter Tight','Roobert',ui-sans-serif,system-ui,sans-serif;">
            Tim <span class="text-[#3398e1] bg-[#c1e1f7] rounded px-2 py-0.5">project</span>
        </h1>
        <p class="mt-2 text-[14px] leading-[1.64] text-[#78716c]">{{ $project->name }} &middot; Owner: {{ $project->owner->name ?? '-' }}</p>

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

        <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_380px]">
            <div class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                <h2 class="text-[20px] text-[#0c0a09]" style="font-family:'Inter Tight','Roobert',ui-sans-serif,system-ui,sans-serif;">
                    Daftar member ({{ $project->members->count() }})
                </h2>

                @if ($project->members->isEmpty())
                    <p class="mt-4 text-[14px] text-[#78716c]">Belum ada member.</p>
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
                                        <p class="truncate text-[12px] text-[#78716c]">{{ $member->email }} &middot; bergabung {{ $member->pivot->created_at?->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @if ($isOwner)
                                    <form method="POST" action="{{ route('projects.members.destroy', [$project, $member]) }}" onsubmit="return confirm('Hapus {{ addslashes($member->name) }}?');" class="shrink-0">
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

            @if ($isOwner)
                <div class="h-fit rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                    <h3 class="text-[16px] font-medium text-[#0c0a09]">Tambah member</h3>
                    <p class="mt-1 text-[13px] text-[#78716c]">Hanya owner yang dapat menambah / menghapus member.</p>
                    <form method="POST" action="{{ route('projects.members.store', $project) }}" class="mt-4 flex flex-col gap-3">
                        @csrf
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.test"
                            class="rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] placeholder-[#78716c] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
                        <button type="submit" class="rounded-full bg-[#3ba6f1] border border-[#3398e1] text-white text-sm font-medium px-4 py-2 hover:opacity-90 transition">Tambah</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
