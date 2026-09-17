@extends('layouts.app')

@section('title', (!empty($parentProject) ? 'Tasks di '.$parentProject->name : 'Semua Tasks').' - '.config('app.name', 'Jara'))

@section('content')
<div class="bg-[#fafaf9]">
    <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8 py-12">
        @if (!empty($parentProject))
            <p class="text-[14px] text-[#78716c]">
                <a href="{{ route('projects.index') }}" class="hover:text-[#0c0a09]">Projects</a>
                <span class="mx-1">›</span>
                <a href="{{ route('projects.show', $parentProject) }}" class="hover:text-[#0c0a09]">{{ $parentProject->name }}</a>
                <span class="mx-1">›</span><span class="text-[#0c0a09]">Tasks</span>
            </p>
        @endif

        @if (session('status'))
            <div class="mt-4 rounded-[10px] border border-[#e8e6e5] bg-white px-4 py-3 text-sm text-[#0c0a09] shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-6 mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-[32px] leading-[1.25] tracking-[-0.8px] font-normal text-[#0c0a09]" style="font-family:'Inter Tight','Roobert',ui-sans-serif,system-ui,sans-serif;">
                    @if (!empty($parentProject))
                        Tasks di <span class="text-[#3398e1] bg-[#c1e1f7] rounded px-2 py-0.5">{{ $parentProject->name }}</span>
                    @else
                        Semua task dengan <span class="text-[#3398e1] bg-[#c1e1f7] rounded px-2 py-0.5">fokus</span>
                    @endif
                </h1>
                <p class="mt-2 text-[14px] leading-[1.64] text-[#78716c] max-w-xl">
                    @if (!empty($parentProject))
                        Task-task yang menjadi bagian dari project ini. Untuk pindah project, <a href="{{ route('projects.index') }}" class="text-[#3398e1] hover:underline">pilih project lain &rarr;</a>
                    @else
                        CRUD task + prioritas, deadline, dan status. Setiap baris selalu menunjukkan project induknya.
                    @endif
                </p>
            </div>
            @if (!empty($parentProject))
                <a href="{{ route('projects.tasks.create', $parentProject) }}" class="inline-flex items-center rounded-full bg-[#3ba6f1] border border-[#3398e1] text-white text-sm font-medium px-4 py-2 hover:opacity-90 transition">+ Task di project ini</a>
            @else
                <a href="{{ route('tasks.create', ['project_id' => $project?->id]) }}" class="inline-flex items-center rounded-full bg-[#3ba6f1] border border-[#3398e1] text-white text-sm font-medium px-4 py-2 hover:opacity-90 transition">+ New task</a>
            @endif
        </div>

        {{-- Filter bar --}}
        @if (!empty($parentProject))
        <form method="GET" action="{{ route('projects.tasks.index', $parentProject) }}" class="rounded-[10px] border border-[#e8e6e5] bg-white p-4 mb-6 flex flex-wrap gap-3 items-end shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
        @else
        <form method="GET" action="{{ route('tasks.index') }}" class="rounded-[10px] border border-[#e8e6e5] bg-white p-4 mb-6 flex flex-wrap gap-3 items-end shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
            <label class="flex flex-col gap-1 text-sm min-w-[180px] flex-1">
                <span class="text-[12px] text-[#78716c]">Project induk</span>
                <select name="project_id" onchange="this.form.submit()" class="rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
                    <option value="">Semua project</option>
                    @foreach ($projects as $p)
                        <option value="{{ $p->id }}" {{ ($project && $project->id == $p->id) ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </label>
        @endif
            <label class="flex flex-col gap-1 text-sm">
                <span class="text-[12px] text-[#78716c]">Status</span>
                <select name="status" onchange="this.form.submit()" class="rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </label>
            <label class="flex flex-col gap-1 text-sm">
                <span class="text-[12px] text-[#78716c]">Priority</span>
                <select name="priority" onchange="this.form.submit()" class="rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
                    <option value="">Semua</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </label>
            <label class="flex flex-col gap-1 text-sm flex-1 min-w-[180px]">
                <span class="text-[12px] text-[#78716c]">Cari</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Judul atau deskripsi…" class="rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] placeholder-[#78716c] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
            </label>
            <button class="rounded-full border border-[#e8e6e5] px-4 py-2 text-sm text-[#0c0a09] hover:bg-[#fafaf9] transition">Filter</button>
            @if (request()->hasAny(['status', 'priority', 'q', 'project_id']))
                <a href="{{ !empty($parentProject) ? route('projects.tasks.index', $parentProject) : route('tasks.index') }}" class="text-sm text-[#3398e1] hover:underline">Reset</a>
            @endif
        </form>

        {{-- Task list --}}
        <div class="rounded-[10px] border border-[#e8e6e5] bg-white shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px] overflow-hidden">
            @forelse ($tasks as $task)
                <div class="flex items-start gap-4 px-6 py-5 hover:bg-[#fafaf9] transition {{ $loop->first ? '' : 'border-t border-[#e8e6e5]' }}">
                    <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="pt-1 shrink-0">
                        @csrf @method('PATCH')
                        <button title="Tandai selesai / pending" class="flex h-5 w-5 items-center justify-center rounded-full text-white"
                            style="border:1px solid {{ $task->status === 'completed' ? '#3ba6f1' : '#d6d3d1' }};background:{{ $task->status === 'completed' ? '#3ba6f1' : '#fff' }};">
                            @if ($task->status === 'completed') <span class="text-[11px] leading-none">✓</span> @endif
                        </button>
                    </form>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <a href="{{ route('projects.show', $task->project_id) }}" class="rounded-full px-2.5 py-0.5 text-[11px] font-medium bg-[#e8e6e5] text-[#0c0a09] hover:underline" title="Buka project induk">
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
                                    @if ($task->status === 'pending' && $task->deadline->isPast()) &middot; Overdue @endif
                                </span>
                            @endif
                        </div>
                        <p class="font-medium text-[#0c0a09]" style="{{ $task->status === 'completed' ? 'text-decoration:line-through;opacity:.6;' : '' }}">{{ $task->title }}</p>
                        @if ($task->description)
                            <p class="text-sm mt-1 line-clamp-2 text-[#78716c]">{{ $task->description }}</p>
                        @endif
                        <p class="text-[11px] mt-1.5 text-[#a8a29e]">dibuat {{ $task->created_at->diffForHumans() }}</p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('tasks.edit', $task) }}" class="text-xs font-medium px-3 py-1.5 rounded-full border border-[#e8e6e5] text-[#0c0a09] hover:bg-[#fafaf9]">Edit</a>
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Hapus task “{{ addslashes($task->title) }}”?')">
                            @csrf @method('DELETE')
                            <button class="text-xs font-medium px-3 py-1.5 rounded-full border border-[#e8e6e5] text-[#78716c] hover:bg-[#fafaf9]">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-6 py-14 text-center">
                    <p class="text-[20px] text-[#0c0a09]" style="font-family:'Inter Tight','Roobert',ui-sans-serif,system-ui,sans-serif;">Belum ada task di sini</p>
                    <p class="mt-1 text-sm text-[#78716c]">Ubah filter atau buat task baru.</p>
                    @if (!empty($parentProject))
                        <a href="{{ route('projects.tasks.create', $parentProject) }}" class="mt-4 inline-flex items-center rounded-full bg-[#3ba6f1] border border-[#3398e1] text-white text-sm font-medium px-4 py-2 hover:opacity-90 transition">+ Buat task di {{ $parentProject->name }}</a>
                    @else
                        <a href="{{ route('tasks.create', ['project_id' => $project?->id]) }}" class="mt-4 inline-flex items-center rounded-full bg-[#3ba6f1] border border-[#3398e1] text-white text-sm font-medium px-4 py-2 hover:opacity-90 transition">+ Buat task</a>
                    @endif
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $tasks->links() }}
        </div>
    </div>
</div>
@endsection
