@extends('layouts.app')

@section('title', $project->name.' — Jara')

@section('content')
{{-- Breadcrumb: Projects › Nama Project --}}
<p class="text-xs mb-3" style="color:#a8a29e;">
    <a href="{{ route('projects.index') }}" class="hover:underline" style="color:#3398e1;">Projects</a>
    <span class="mx-1">›</span>
    <span style="color:#0c0a09;" class="font-medium">{{ $project->name }}</span>
</p>

{{-- Header project = wadah --}}
<div class="card-flat p-6 mb-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="min-w-0 flex-1">
            <p class="text-[12px] font-medium tracking-[0.08em] uppercase mb-2" style="color:#a8a29e;">Project · wadah dari {{ $total }} task</p>
            <h1 class="font-display" style="font-size:32px;line-height:1.25;letter-spacing:-0.8px;color:#0c0a09;">{{ $project->name }}</h1>
            @if ($project->description)
                <p class="mt-2 text-sm" style="color:#78716c;max-width:640px;">{{ $project->description }}</p>
            @endif
            <p class="text-xs mt-2" style="color:#a8a29e;">
                Owner: {{ $project->owner?->name ?? '—' }} · {{ $project->members->count() }} member · dibuat {{ $project->created_at->diffForHumans() }}
            </p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('projects.edit', $project) }}" class="btn-ghost !py-2">Edit project</a>
            <a href="{{ route('projects.tasks.create', $project) }}" class="btn-primary !py-2">+ Task di project ini</a>
        </div>
    </div>

    <div class="mt-5 flex items-center gap-4">
        <div class="flex-1 h-2.5 rounded-full overflow-hidden" style="background:#e8e6e5;">
            <div class="h-full rounded-full" style="width:{{ $progress }}%;background:#3ba6f1;"></div>
        </div>
        <span class="rounded-full px-3 py-1 text-sm font-medium shrink-0" style="background:#c1e1f7;color:#0c0a09;">{{ $progress }}%</span>
    </div>
    <div class="mt-3 flex flex-wrap gap-4 text-sm" style="color:#78716c;">
        <span><strong style="color:#0c0a09;">{{ $total }}</strong> total</span>
        <span><strong style="color:#0c0a09;">{{ $completed }}</strong> selesai</span>
        <span><strong style="color:#0c0a09;">{{ $pending }}</strong> pending</span>
        <span><strong style="color:#0c0a09;">{{ $overdue }}</strong> overdue</span>
    </div>
</div>

{{-- Filter task DI DALAM project ini saja --}}
<form method="GET" action="{{ route('projects.show', $project) }}" class="card-flat p-4 mb-4 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs font-medium mb-1.5" style="color:#78716c;">Status</label>
        <select name="status" class="input-seline" onchange="this.form.submit()">
            <option value="">Semua</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium mb-1.5" style="color:#78716c;">Priority</label>
        <select name="priority" class="input-seline" onchange="this.form.submit()">
            <option value="">Semua</option>
            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
        </select>
    </div>
    <a href="{{ route('tasks.index') }}" class="text-sm font-medium ml-auto" style="color:#3398e1;">Lihat semua task lintas project →</a>
</form>

{{-- Daftar tasks MILIK project ini --}}
<div class="card-flat overflow-hidden">
    <div class="px-6 py-3 text-[12px] font-medium uppercase tracking-widest" style="color:#a8a29e;border-bottom:1px solid #e8e6e5;">
        Tasks di “{{ $project->name }}” ({{ $tasks->total() }})
    </div>
    @forelse ($tasks as $task)
        <div class="flex items-start gap-4 px-6 py-5 hover:bg-[#fafaf9] transition" style="border-top:1px solid #e8e6e5;">
            <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="pt-1">
                @csrf @method('PATCH')
                <button title="Tandai selesai / pending" class="flex h-5 w-5 items-center justify-center rounded-full"
                    style="border:1px solid {{ $task->status === 'completed' ? '#3ba6f1' : '#d6d3d1' }};background:{{ $task->status === 'completed' ? '#3ba6f1' : '#fff' }};color:#fff;">
                    @if ($task->status === 'completed') <span class="text-[11px] leading-none">✓</span> @endif
                </button>
            </form>
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <span class="rounded-full px-2.5 py-0.5 text-[11px] font-medium"
                        style="{{ $task->priority === 'high' ? 'background:#0c0a09;color:#fff;' : ($task->priority === 'medium' ? 'background:#c1e1f7;color:#0c0a09;' : 'border:1px solid #e8e6e5;color:#78716c;background:#fff;') }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                    <span class="rounded-full px-2.5 py-0.5 text-[11px] font-medium"
                        style="{{ $task->status === 'completed' ? 'background:#1c1917;color:#fff;' : 'border:1px solid #e8e6e5;color:#78716c;' }}">
                        {{ ucfirst($task->status) }}
                    </span>
                    @if ($task->deadline)
                        <span class="text-[11px]" style="color:{{ $task->status === 'pending' && $task->deadline->isPast() ? '#b3261e' : '#78716c' }};">
                            ⏰ {{ $task->deadline->format('d M Y H:i') }}
                            @if ($task->status === 'pending' && $task->deadline->isPast()) · Overdue @endif
                        </span>
                    @endif
                </div>
                <p class="font-medium" style="color:#0c0a09;{{ $task->status === 'completed' ? 'text-decoration:line-through;opacity:.6;' : '' }}">{{ $task->title }}</p>
                @if ($task->description)
                    <p class="text-sm mt-1 line-clamp-2" style="color:#78716c;">{{ $task->description }}</p>
                @endif
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('tasks.edit', $task) }}" class="text-xs font-medium px-3 py-1.5 rounded-full" style="border:1px solid #e8e6e5;color:#0c0a09;">Edit</a>
                <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Hapus task “{{ addslashes($task->title) }}” dari project ini?')">
                    @csrf @method('DELETE')
                    <button class="text-xs font-medium px-3 py-1.5 rounded-full" style="border:1px solid #e8e6e5;color:#78716c;">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <div class="px-6 py-12 text-center">
            <p class="font-display mb-2" style="font-size:20px;color:#0c0a09;">Project ini belum punya task</p>
            <p class="text-sm mb-4" style="color:#78716c;">Tambahkan task pertama ke “{{ $project->name }}”.</p>
            <a href="{{ route('projects.tasks.create', $project) }}" class="btn-primary">+ Tambah task di sini</a>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $tasks->links() }}
</div>

{{-- Danger zone: hapus project --}}
<div class="card-flat p-6 mt-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="font-medium text-sm" style="color:#0c0a09;">Hapus project?</p>
            <p class="text-xs" style="color:#78716c;">Semua {{ $total }} task di dalamnya ikut terhapus (cascade).</p>
        </div>
        <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Hapus project “{{ addslashes($project->name) }}” beserta {{ $total }} task-nya? Tindakan ini permanen.')">
            @csrf @method('DELETE')
            <button class="text-xs font-medium px-4 py-2 rounded-full" style="border:1px solid #e8e6e5;color:#b3261e;">Hapus project</button>
        </form>
    </div>
</div>
@endsection
