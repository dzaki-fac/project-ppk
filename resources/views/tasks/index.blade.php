@extends('layouts.app')

@section('title', (!empty($parentProject) ? 'Tasks di '.$parentProject->name : 'Semua Tasks').' — Jara')

@section('content')
@if (!empty($parentProject))
    <p class="text-xs mb-3" style="color:#a8a29e;">
        <a href="{{ route('projects.index') }}" class="hover:underline" style="color:#3398e1;">Projects</a>
        <span class="mx-1">›</span>
        <a href="{{ route('projects.show', $parentProject) }}" class="hover:underline" style="color:#3398e1;">{{ $parentProject->name }}</a>
        <span class="mx-1">›</span><span class="font-medium" style="color:#0c0a09;">Tasks</span>
    </p>
@endif

<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <p class="text-[12px] font-medium tracking-[0.08em] uppercase mb-2" style="color:#a8a29e;">
            {{ !empty($parentProject) ? 'Project · '.$parentProject->name : 'Jara · Semua tasks lintas project' }}
        </p>
        <h1 class="font-display" style="font-size:32px;line-height:1.25;letter-spacing:-0.8px;color:#0c0a09;">
            @if (!empty($parentProject))
                Tasks di <span class="highlight-span">{{ $parentProject->name }}</span>
            @else
                Semua task dengan <span class="highlight-span">fokus</span>
            @endif
        </h1>
        <p class="mt-2 text-sm" style="color:#78716c;">
            @if (!empty($parentProject))
                Task-task yang menjadi bagian dari project ini. Untuk pindah project, <a href="{{ route('projects.index') }}" class="font-medium" style="color:#3398e1;">pilih project lain →</a>
            @else
                CRUD task + prioritas, deadline, dan status. Setiap baris selalu menunjukkan project induknya.
            @endif
        </p>
    </div>
    @if (!empty($parentProject))
        <a href="{{ route('projects.tasks.create', $parentProject) }}" class="btn-primary">+ Task di project ini</a>
    @else
        <a href="{{ route('tasks.create', ['project_id' => $project?->id]) }}" class="btn-primary">+ New task</a>
    @endif
</div>

{{-- Filter bar --}}
@if (!empty($parentProject))
<form method="GET" action="{{ route('projects.tasks.index', $parentProject) }}" class="card-flat p-4 mb-6 flex flex-wrap gap-3 items-end">
@else
<form method="GET" action="{{ route('tasks.index') }}" class="card-flat p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div class="min-w-[180px] flex-1">
        <label class="block text-xs font-medium mb-1.5" style="color:#78716c;">Project induk</label>
        <select name="project_id" class="input-seline" onchange="this.form.submit()">
            <option value="">Semua project</option>
            @foreach ($projects as $p)
                <option value="{{ $p->id }}" {{ ($project && $project->id == $p->id) ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
        </select>
    </div>
@endif
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
    <div class="flex-1 min-w-[180px]">
        <label class="block text-xs font-medium mb-1.5" style="color:#78716c;">Cari</label>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Judul atau deskripsi…" class="input-seline">
    </div>
    <button class="btn-ghost">Filter</button>
    @if (request()->hasAny(['status', 'priority', 'q', 'project_id']))
        <a href="{{ !empty($parentProject) ? route('projects.tasks.index', $parentProject) : route('tasks.index') }}" class="text-sm font-medium" style="color:#3398e1;">Reset</a>
    @endif
</form>

{{-- Task list --}}
<div class="card-flat overflow-hidden">
    @forelse ($tasks as $task)
        <div class="flex items-start gap-4 px-6 py-5 hover:bg-[#fafaf9] transition" style="border-top:1px solid #e8e6e5;{{ $loop->first ? 'border-top:none;' : '' }}">
            <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="pt-1">
                @csrf @method('PATCH')
                <button title="Tandai selesai / pending" class="flex h-5 w-5 items-center justify-center rounded-full"
                    style="border:1px solid {{ $task->status === 'completed' ? '#3ba6f1' : '#d6d3d1' }};background:{{ $task->status === 'completed' ? '#3ba6f1' : '#fff' }};color:#fff;">
                    @if ($task->status === 'completed') <span class="text-[11px] leading-none">✓</span> @endif
                </button>
            </form>

            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <a href="{{ route('projects.show', $task->project_id) }}" class="rounded-full px-2.5 py-0.5 text-[11px] font-medium hover:underline" style="background:#e8e6e5;color:#0c0a09;" title="Buka project induk">
                        📁 {{ $task->project?->name ?? '—' }}
                    </a>
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
                <p class="text-[11px] mt-1.5" style="color:#a8a29e;">dibuat {{ $task->created_at->diffForHumans() }}</p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('tasks.edit', $task) }}" class="text-xs font-medium px-3 py-1.5 rounded-full" style="border:1px solid #e8e6e5;color:#0c0a09;">Edit</a>
                <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Hapus task “{{ addslashes($task->title) }}”?')">
                    @csrf @method('DELETE')
                    <button class="text-xs font-medium px-3 py-1.5 rounded-full" style="border:1px solid #e8e6e5;color:#78716c;">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <div class="px-6 py-14 text-center">
            <p class="font-display mb-2" style="font-size:20px;color:#0c0a09;">Belum ada task di sini</p>
            <p class="text-sm mb-4" style="color:#78716c;">Ubah filter atau buat task baru.</p>
            @if (!empty($parentProject))
                <a href="{{ route('projects.tasks.create', $parentProject) }}" class="btn-primary">+ Buat task di {{ $parentProject->name }}</a>
            @else
                <a href="{{ route('tasks.create', ['project_id' => $project?->id]) }}" class="btn-primary">+ Buat task</a>
            @endif
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $tasks->links() }}
</div>
@endsection
