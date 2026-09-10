@extends('layouts.app')

@section('title', 'Projects - ' . config('app.name', 'Jara'))

@section('content')
<div class="bg-[#fafaf9]">
    <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8 py-12">
        {{-- Headline: satu highlight span per DESIGN.md --}}
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-[32px] leading-[1.25] tracking-[-0.8px] font-normal text-[#0c0a09]" style="font-family:'Inter Tight','Roobert',ui-sans-serif,system-ui,sans-serif;">
                    Projects <span class="text-[#3398e1] bg-[#c1e1f7] rounded px-2 py-0.5">terorganisir</span> dalam satu tempat
                </h1>
                <p class="mt-2 text-[14px] leading-[1.64] text-[#78716c] max-w-xl">
                    Kelola project milikmu dan project yang kamu ikuti sebagai member. Pilih project untuk melihat detail dan tim.
                </p>
            </div>
            <a href="{{ route('projects.create') }}"
               class="inline-flex items-center rounded-full bg-[#3ba6f1] border border-[#3398e1] text-white text-sm font-medium px-4 py-2 hover:opacity-90 transition">
                + Buat project
            </a>
        </div>

        @if (session('status'))
            <div class="mb-6 rounded-[10px] border border-[#e8e6e5] bg-white px-4 py-3 text-sm text-[#0c0a09] shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                {{ session('status') }}
            </div>
        @endif

        {{-- Project milik saya --}}
        <h2 class="text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09] mb-4" style="font-family:'Inter Tight','Roobert',ui-sans-serif,system-ui,sans-serif;">
            Project milik saya ({{ $ownedProjects->count() }})
        </h2>

        @if ($ownedProjects->isEmpty())
            <div class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 text-sm text-[#78716c] shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px] mb-10">
                Belum ada project. Buat project pertama untuk memulai kolaborasi tim.
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3 mb-10">
                @foreach ($ownedProjects as $project)
                    <article class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px] flex flex-col gap-3">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="text-[18px] tracking-[-0.017em] text-[#0c0a09] font-medium leading-snug">
                                <a href="{{ route('projects.show', $project) }}" class="hover:text-[#3398e1]">{{ $project->name }}</a>
                            </h3>
                            <span class="shrink-0 inline-flex items-center rounded-full border border-[#e8e6e5] px-2 py-0.5 text-[10px] tracking-[0.025em] text-[#78716c] uppercase">Owner</span>
                        </div>
                        <p class="text-[14px] leading-[1.64] text-[#78716c] line-clamp-3 min-h-[1.5rem]">
                            {{ $project->description ?: 'Tanpa deskripsi.' }}
                        </p>
                        <p class="text-[12px] text-[#a8a29e]">{{ $project->members_count }} member &middot; dibuat {{ $project->created_at->diffForHumans() }}</p>
                        <div class="mt-1 flex flex-wrap items-center gap-2 border-t border-[#e8e6e5] pt-4">
                            <a href="{{ route('projects.show', $project) }}" class="rounded-full border border-[#e8e6e5] px-4 py-2 text-sm text-[#0c0a09] hover:bg-[#fafaf9] transition">Detail</a>
                            <a href="{{ route('projects.edit', $project) }}" class="rounded-full border border-[#e8e6e5] px-4 py-2 text-sm text-[#0c0a09] hover:bg-[#fafaf9] transition">Edit</a>
                            <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Hapus project {{ addslashes($project->name) }}?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-full border border-[#e8e6e5] px-4 py-2 text-sm text-[#0c0a09] hover:bg-[#fafaf9] transition">Hapus</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        {{-- Project yang diikuti --}}
        <h2 class="text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09] mb-4" style="font-family:'Inter Tight','Roobert',ui-sans-serif,system-ui,sans-serif;">
            Diikuti sebagai member ({{ $memberProjects->count() }})
        </h2>

        @if ($memberProjects->isEmpty())
            <div class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 text-sm text-[#78716c] shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                Kamu belum menjadi member project manapun.
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($memberProjects as $project)
                    <article class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px] flex flex-col gap-3">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="text-[18px] tracking-[-0.017em] text-[#0c0a09] font-medium leading-snug">
                                <a href="{{ route('projects.show', $project) }}" class="hover:text-[#3398e1]">{{ $project->name }}</a>
                            </h3>
                            <span class="shrink-0 inline-flex items-center rounded-full border border-[#e8e6e5] px-2 py-0.5 text-[10px] tracking-[0.025em] text-[#78716c] uppercase">Member</span>
                        </div>
                        <p class="text-[14px] leading-[1.64] text-[#78716c] line-clamp-3 min-h-[1.5rem]">
                            {{ $project->description ?: 'Tanpa deskripsi.' }}
                        </p>
                        <p class="text-[12px] text-[#a8a29e]">Owner: {{ $project->owner->name ?? '-' }} &middot; {{ $project->members_count }} member</p>
                        <div class="mt-1 flex flex-wrap items-center gap-2 border-t border-[#e8e6e5] pt-4">
                            <a href="{{ route('projects.show', $project) }}" class="rounded-full bg-[#3ba6f1] border border-[#3398e1] text-white px-4 py-2 text-sm font-medium hover:opacity-90 transition">Buka</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
