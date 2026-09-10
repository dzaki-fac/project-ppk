@extends('layouts.app')

@section('title', 'Projects — Jara')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <p class="text-[12px] font-medium tracking-[0.08em] uppercase mb-2" style="color:#a8a29e;">Jara · Projects</p>
        <h1 class="font-display" style="font-size:32px;line-height:1.25;letter-spacing:-0.8px;color:#0c0a09;">
            Project sebagai <span class="highlight-span">wadah</span>
        </h1>
        <p class="mt-2 text-sm" style="color:#78716c;">Satu project terdiri dari banyak task. Pilih project untuk melihat &amp; mengelola task di dalamnya.</p>
    </div>
    <a href="{{ route('projects.create') }}" class="btn-primary">+ New project</a>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse ($projects as $p)
        @php $pct = $p->tasks_count > 0 ? (int) round($p->completed_tasks_count / $p->tasks_count * 100) : 0; @endphp
        <div class="card-flat p-6 flex flex-col">
            <div class="flex items-start justify-between gap-3 mb-3">
                <h2 class="font-display leading-snug" style="font-size:20px;color:#0c0a09;">
                    <a href="{{ route('projects.show', $p) }}" class="hover:underline">{{ $p->name }}</a>
                </h2>
                <span class="rounded-full px-2.5 py-0.5 text-[11px] font-medium shrink-0" style="background:#c1e1f7;color:#0c0a09;">{{ $pct }}%</span>
            </div>
            @if ($p->description)
                <p class="text-sm mb-4 line-clamp-2" style="color:#78716c;">{{ $p->description }}</p>
            @endif
            <div class="h-2 rounded-full overflow-hidden mb-3" style="background:#e8e6e5;">
                <div class="h-full rounded-full" style="width:{{ $pct }}%;background:#3ba6f1;"></div>
            </div>
            <p class="text-xs mb-4" style="color:#78716c;">
                {{ $p->completed_tasks_count }}/{{ $p->tasks_count }} task selesai · {{ $p->pending_tasks_count }} pending
                @if ($p->owner) · owner: {{ $p->owner->name }} @endif
            </p>
            <div class="mt-auto flex items-center gap-2">
                <a href="{{ route('projects.show', $p) }}" class="btn-primary !px-4 !py-1.5 !text-xs flex-1 justify-center">Buka project →</a>
                <a href="{{ route('projects.edit', $p) }}" class="text-xs font-medium px-3 py-1.5 rounded-full" style="border:1px solid #e8e6e5;color:#0c0a09;">Edit</a>
            </div>
        </div>
    @empty
        <div class="card-flat p-10 text-center sm:col-span-2 lg:col-span-3">
            <p class="font-display mb-2" style="font-size:20px;color:#0c0a09;">Belum ada project</p>
            <p class="text-sm mb-4" style="color:#78716c;">Buat project dulu, lalu isi dengan task-task.</p>
            <a href="{{ route('projects.create') }}" class="btn-primary">+ Buat project pertama</a>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $projects->links() }}
</div>
@endsection
