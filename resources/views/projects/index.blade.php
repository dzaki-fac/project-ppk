@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="font-display text-[32px] leading-[1.25] font-normal">Project <span class="text-[#3398e1] bg-[#c1e1f7] px-2 py-0.5 rounded">saya</span></h1>
        <p class="mt-2 text-[14px] leading-[1.64] text-[#78716c] max-w-[560px]">Buat list baru, kelola yang Anda miliki, dan lihat project tempat Anda diundang sebagai anggota.</p>
    </div>
    <a href="{{ route('projects.create') }}" class="px-4 py-2 text-[14px] font-medium text-white bg-[#3ba6f1] border border-[#3398e1] rounded-full hover:opacity-90">+ Project baru</a>
</div>

<h2 class="mt-10 text-[20px] font-normal tracking-[-0.1px]">Dimiliki</h2>
@if ($owned->isEmpty())
    <div class="mt-3 bg-white border border-[#e8e6e5] rounded-[10px] p-6 text-[14px] text-[#78716c] shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">Belum ada project. Buat project pertama Anda.</div>
@else
    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($owned as $project)
            <a href="{{ route('projects.show', $project) }}" class="bg-white border border-[#e8e6e5] rounded-[10px] p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px] hover:border-[#d6d3d1] transition block">
                <p class="text-[10px] tracking-wide uppercase text-[#a8a29e]">Owner • {{ $project->members_count }} anggota • {{ $project->tasks_count }} tugas</p>
                <h3 class="mt-2 text-[16px] font-medium">{{ $project->name }}</h3>
                <p class="mt-1 text-[14px] text-[#78716c] line-clamp-2">{{ $project->description ?: 'Tanpa deskripsi.' }}</p>
            </a>
        @endforeach
    </div>
@endif

<h2 class="mt-10 text-[20px] font-normal tracking-[-0.1px]">Sebagai anggota</h2>
@if ($memberOf->isEmpty())
    <div class="mt-3 bg-white border border-[#e8e6e5] rounded-[10px] p-6 text-[14px] text-[#78716c] shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">Anda belum diundang ke project mana pun.</div>
@else
    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($memberOf as $project)
            <a href="{{ route('projects.show', $project) }}" class="bg-white border border-[#e8e6e5] rounded-[10px] p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px] hover:border-[#d6d3d1] transition block">
                <p class="text-[10px] tracking-wide uppercase text-[#a8a29e]">Anggota • Owner: {{ $project->owner->name ?? '-' }}</p>
                <h3 class="mt-2 text-[16px] font-medium">{{ $project->name }}</h3>
                <p class="mt-1 text-[14px] text-[#78716c] line-clamp-2">{{ $project->description ?: 'Tanpa deskripsi.' }}</p>
            </a>
        @endforeach
    </div>
@endif
@endsection
