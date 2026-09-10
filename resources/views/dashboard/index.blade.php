@extends('layouts.app')

@section('title', 'Dashboard — Jara')

@section('content')
{{-- Hero headline — Seline: 32px roobert + 1 highlight span --}}
<div class="mb-8">
    <p class="text-[12px] font-medium tracking-[0.08em] uppercase mb-2" style="color:#a8a29e;">Jara · Dashboard</p>
    <h1 class="font-display" style="font-size:32px;line-height:1.25;letter-spacing:-0.8px;color:#0c0a09;">
        Project progress, <span class="highlight-span">simple &amp; actionable</span>
    </h1>
    <p class="mt-3" style="font-size:16px;color:#78716c;max-width:640px;">
        Pantau semua task dalam satu tempat — total, selesai, prioritas, dan deadline terdekat.
    </p>

    {{-- Project switcher — Tab Pill Group: tiap pill buka DETAIL project (wadah) --}}
    <div class="mt-5 flex flex-wrap items-center gap-2">
        @foreach ($projects as $p)
            <a href="{{ route('projects.show', $p) }}"
               class="px-4 py-2 text-sm rounded-full"
               style="{{ $project && $p->id === $project->id ? 'background:#1c1917;color:#fff;' : 'background:transparent;color:#0c0a09;border:1px solid #e8e6e5;' }}">
                📁 {{ $p->name }}
            </a>
        @endforeach
        <a href="{{ route('projects.index') }}" class="btn-ghost !py-2">All projects →</a>
        <a href="{{ route('tasks.index') }}" class="text-sm font-medium" style="color:#3398e1;">All tasks →</a>
    </div>
</div>

{{-- Stat cards — Flat Content Card --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card-flat p-6">
        <p class="text-[12px] font-medium uppercase tracking-widest" style="color:#a8a29e;">Total task</p>
        <p class="font-display mt-1" style="font-size:32px;color:#0c0a09;">{{ $total }}</p>
        <p class="text-sm" style="color:#78716c;">{{ $project ? 'di '.$project->name : 'semua project' }}</p>
    </div>
    <div class="card-flat p-6">
        <p class="text-[12px] font-medium uppercase tracking-widest" style="color:#a8a29e;">Selesai</p>
        <p class="font-display mt-1" style="font-size:32px;color:#0c0a09;">{{ $completed }}</p>
        <p class="text-sm" style="color:#78716c;">{{ $progress }}% progress</p>
    </div>
    <div class="card-flat p-6">
        <p class="text-[12px] font-medium uppercase tracking-widest" style="color:#a8a29e;">Pending</p>
        <p class="font-display mt-1" style="font-size:32px;color:#0c0a09;">{{ $pending }}</p>
        <p class="text-sm" style="color:#78716c;">{{ $highPriority }} prioritas tinggi</p>
    </div>
    <div class="card-flat p-6">
        <p class="text-[12px] font-medium uppercase tracking-widest" style="color:#a8a29e;">Overdue</p>
        <p class="font-display mt-1" style="font-size:32px;color:#0c0a09;">{{ $overdue }}</p>
        <p class="text-sm" style="color:#78716c;">deadline terlewat</p>
    </div>
</div>

{{-- Progress bar card — Floating feel, 1 highlight --}}
<div class="card-flat p-6 mb-6">
    <div class="flex items-center justify-between mb-3">
        <h2 class="font-display" style="font-size:20px;color:#0c0a09;">Progress {{ $project?->name ?? '' }}</h2>
        <span class="rounded-full px-3 py-1 text-sm font-medium" style="background:#c1e1f7;color:#0c0a09;">{{ $progress }}%</span>
    </div>
    <div class="h-2.5 rounded-full overflow-hidden" style="background:#e8e6e5;">
        <div class="h-full rounded-full" style="width:{{ $progress }}%;background:#3ba6f1;transition:width .4s ease;"></div>
    </div>
    <div class="mt-4 flex flex-wrap gap-4 text-sm" style="color:#78716c;">
        <span><strong style="color:#0c0a09;">{{ $byPriority['high'] }}</strong> high</span>
        <span><strong style="color:#0c0a09;">{{ $byPriority['medium'] }}</strong> medium</span>
        <span><strong style="color:#0c0a09;">{{ $byPriority['low'] }}</strong> low</span>
        <span class="ml-auto">{{ $completed }} dari {{ $total }} task selesai</span>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-4">
    {{-- Upcoming deadlines --}}
    <div class="card-flat p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-display" style="font-size:20px;color:#0c0a09;">Deadline terdekat</h2>
            <a href="{{ route('tasks.index') }}" class="text-sm font-medium" style="color:#3398e1;">Lihat semua →</a>
        </div>
        @forelse ($upcomingTasks as $task)
            <div class="flex items-center gap-3 py-3" style="border-top:1px solid #e8e6e5;">
                <span class="h-2 w-2 rounded-full shrink-0" style="background:{{ $task->priority === 'high' ? '#0c0a09' : ($task->priority === 'medium' ? '#3ba6f1' : '#d6d3d1') }};"></span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium truncate" style="color:#0c0a09;">{{ $task->title }}</p>
                    <p class="text-xs" style="color:#78716c;">
                        <a href="{{ route('projects.show', $task->project_id) }}" class="hover:underline" style="color:#3398e1;">{{ $task->project?->name }}</a>
                        · {{ $task->deadline?->format('d M Y H:i') }} · {{ ucfirst($task->priority) }}
                        @if ($task->deadline && $task->deadline->isPast()) <span class="font-semibold" style="color:#b3261e;">· Overdue</span> @endif
                    </p>
                </div>
                <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                    @csrf @method('PATCH')
                    <button class="btn-ghost !px-3 !py-1.5 !text-xs">Done</button>
                </form>
            </div>
        @empty
            <p class="text-sm py-4" style="color:#78716c;">Tidak ada deadline pending. Semua aman. 🎉</p>
        @endforelse
    </div>

    {{-- Recent tasks --}}
    <div class="card-flat p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-display" style="font-size:20px;color:#0c0a09;">Task terbaru</h2>
            <a href="{{ $project ? route('projects.tasks.create', $project) : route('tasks.create') }}" class="text-sm font-medium" style="color:#3398e1;">+ Tambah task</a>
        </div>
        @forelse ($recentTasks as $task)
            <div class="flex items-center gap-3 py-3" style="border-top:1px solid #e8e6e5;">
                <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                    @csrf @method('PATCH')
                    <button title="Toggle selesai" class="flex h-5 w-5 items-center justify-center rounded-full shrink-0"
                        style="border:1px solid {{ $task->status === 'completed' ? '#3ba6f1' : '#d6d3d1' }};background:{{ $task->status === 'completed' ? '#3ba6f1' : '#fff' }};color:#fff;">
                        @if ($task->status === 'completed') <span class="text-[11px] leading-none">✓</span> @endif
                    </button>
                </form>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium truncate" style="color:#0c0a09;{{ $task->status === 'completed' ? 'text-decoration:line-through;opacity:.6;' : '' }}">{{ $task->title }}</p>
                    <p class="text-xs" style="color:#78716c;"><a href="{{ route('projects.show', $task->project_id) }}" class="hover:underline" style="color:#3398e1;">{{ $task->project?->name }}</a> · {{ ucfirst($task->priority) }} · {{ ucfirst($task->status) }}</p>
                </div>
                <a href="{{ route('tasks.edit', $task) }}" class="text-xs font-medium px-3 py-1.5 rounded-full" style="border:1px solid #e8e6e5;color:#0c0a09;">Edit</a>
            </div>
        @empty
            <div class="py-6 text-center">
                <p class="text-sm mb-3" style="color:#78716c;">Belum ada task. Mulai dari satu langkah kecil.</p>
                <a href="{{ $project ? route('projects.tasks.create', $project) : route('tasks.create') }}" class="btn-primary">+ Buat task pertama</a>
            </div>
        @endforelse
    </div>
</div>

{{-- Progress per project --}}
@if ($projects->count() > 1)
<div class="card-flat p-6 mt-6">
    <h2 class="font-display mb-4" style="font-size:20px;color:#0c0a09;">Progress per project</h2>
    @foreach ($projects as $p)
        @php $pct = $p->tasks_count > 0 ? (int) round($p->completed_tasks_count / $p->tasks_count * 100) : 0; @endphp
        <div class="flex items-center gap-4 py-3" style="border-top:1px solid #e8e6e5;">
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between text-sm mb-1.5">
                    <span class="font-medium truncate" style="color:#0c0a09;">{{ $p->name }}</span>
                    <span style="color:#78716c;">{{ $p->completed_tasks_count }}/{{ $p->tasks_count }} · {{ $pct }}%</span>
                </div>
                <div class="h-2 rounded-full overflow-hidden" style="background:#e8e6e5;">
                    <div class="h-full rounded-full" style="width:{{ $pct }}%;background:#3ba6f1;"></div>
                </div>
            </div>
            <a href="{{ route('projects.show', $p->id) }}" class="text-xs font-medium px-3 py-1.5 rounded-full shrink-0" style="border:1px solid #e8e6e5;">Buka project →</a>
        </div>
    @endforeach
</div>
@endif
@endsection
