@extends('layouts.app')

@section('title', $project->name)

@section('content')
@php $isOwner = $project->owner_id === auth()->id(); @endphp

<div class="flex flex-wrap items-start justify-between gap-4">
    <div class="max-w-[640px]">
        <p class="text-[12px] text-[#78716c]"><a href="{{ route('projects.index') }}" class="hover:text-[#0c0a09]">← Projects</a> • {{ $isOwner ? 'Owner' : 'Anggota' }}</p>
        <h1 class="font-display mt-2 text-[32px] leading-[1.25] font-normal">{{ $project->name }}</h1>
        <p class="mt-2 text-[14px] leading-[1.64] text-[#78716c]">{{ $project->description ?: 'Tanpa deskripsi.' }}</p>
        <p class="mt-2 text-[12px] text-[#a8a29e]">Owner: {{ $project->owner->name }} ({{ $project->owner->email }}) • {{ $total ?? $project->tasks->count() }} tugas • {{ $project->members->count() }} anggota</p>
        @php $progress = $progress ?? 0; $done = $done ?? 0; $total = $total ?? $project->tasks->count(); @endphp
        <div class="mt-3 max-w-[420px]">
            <div class="flex items-center justify-between text-[12px] text-[#78716c]">
                <span>Progress</span>
                <span>{{ $done }}/{{ $total }} • {{ $progress }}%</span>
            </div>
            <div class="mt-1 h-2 overflow-hidden rounded-full bg-[#e8e6e5]">
                <div class="h-full rounded-full bg-[#3ba6f1]" style="width: {{ $progress }}%"></div>
            </div>
        </div>
    </div>
    @if ($isOwner)
        <div class="flex items-center gap-2">
            <a href="{{ route('projects.edit', $project) }}" class="px-4 py-2 text-[14px] text-[#0c0a09] border border-[#e8e6e5] rounded-full bg-white hover:border-[#d6d3d1]">Edit</a>
            <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Hapus project ini beserta tugas & anggotanya?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-[14px] text-[#0c0a09] border border-[#e8e6e5] rounded-full bg-white hover:border-[#d6d3d1]">Hapus</button>
            </form>
        </div>
    @endif
</div>

<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="bg-white border border-[#e8e6e5] rounded-[10px] p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
        <h2 class="text-[20px] font-normal">Anggota</h2>
        <p class="mt-1 text-[14px] text-[#78716c]">Hanya owner yang dapat menambah / menghapus.</p>

        @if ($project->members->isEmpty())
            <p class="mt-4 text-[14px] text-[#78716c]">Belum ada anggota.</p>
        @else
            <ul class="mt-4 space-y-2">
                @foreach ($project->members as $member)
                    <li class="flex items-center justify-between gap-3 border border-[#e8e6e5] rounded-[10px] px-3 py-2">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#3ba6f1]/10 text-[#3398e1] text-[13px] font-semibold">{{ strtoupper(mb_substr($member->name, 0, 1)) }}</span>
                            <div class="min-w-0">
                                <p class="truncate text-[14px] font-medium">{{ $member->name }}</p>
                                <p class="truncate text-[12px] text-[#78716c]">{{ $member->email }}</p>
                            </div>
                        </div>
                        @if ($isOwner)
                            <form method="POST" action="{{ route('projects.members.destroy', [$project, $member]) }}" onsubmit="return confirm('Hapus {{ $member->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-[13px] text-[#78716c] hover:text-[#0c0a09] border border-[#e8e6e5] rounded-full px-3 py-1">Hapus</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        @if ($isOwner)
            <form method="POST" action="{{ route('projects.members.store', $project) }}" class="mt-4 flex gap-2">
                @csrf
                <input name="email" type="email" required placeholder="email anggota..." class="flex-1 bg-white border border-[#d6d3d1] rounded-[6px] px-3 py-2 text-[14px] placeholder:text-[#78716c] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
                <button class="shrink-0 px-4 py-2 text-[14px] font-medium text-white bg-[#3ba6f1] border border-[#3398e1] rounded-full hover:opacity-90">Tambah</button>
            </form>
        @endif
    </div>

    <div class="bg-white border border-[#e8e6e5] rounded-[10px] p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
        <h2 class="text-[20px] font-normal">Tugas</h2>
        <p class="mt-1 text-[14px] text-[#78716c]">{{ $project->tasks->count() }} tugas di project ini.</p>

        <form method="POST" action="{{ route('projects.tasks.store', $project) }}" class="mt-4 border border-[#e8e6e5] rounded-[10px] p-3 space-y-2">
            @csrf
            <input name="title" required maxlength="255" placeholder="Judul tugas..." class="w-full bg-white border border-[#d6d3d1] rounded-[6px] px-3 py-2 text-[14px] placeholder:text-[#78716c] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
            <input name="description" placeholder="Deskripsi (opsional)..." class="w-full bg-white border border-[#d6d3d1] rounded-[6px] px-3 py-2 text-[14px] placeholder:text-[#78716c] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
            <div class="flex gap-2">
                <select name="priority" required class="flex-1 bg-white border border-[#d6d3d1] rounded-[6px] px-3 py-2 text-[14px] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
                <input name="deadline" type="datetime-local" class="flex-1 bg-white border border-[#d6d3d1] rounded-[6px] px-3 py-2 text-[14px] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
            </div>
            <button class="w-full px-4 py-2 text-[14px] font-medium text-white bg-[#3ba6f1] border border-[#3398e1] rounded-full hover:opacity-90">Tambah tugas</button>
        </form>

        @if ($project->tasks->isEmpty())
            <p class="mt-4 text-[14px] text-[#78716c]">Belum ada tugas.</p>
        @else
            <ul class="mt-4 space-y-2">
                @foreach ($project->tasks as $task)
                    @php
                        $priorityColor = $task->priority === 'high' ? 'text-[#F53003] border-[#F53003]/20 bg-[#F53003]/10' : ($task->priority === 'medium' ? 'text-[#d97706] border-[#e8e6e5] bg-[#fef3c7]' : 'text-[#78716c] border-[#e8e6e5] bg-white');
                        $isOverdue = $task->deadline && $task->status !== 'completed' && $task->deadline->isPast();
                    @endphp
                    <li class="border border-[#e8e6e5] rounded-[10px] px-3 py-2 {{ $task->status === 'completed' ? 'opacity-70' : '' }}">
                        <div class="flex items-center justify-between gap-2">
                            <form method="POST" action="{{ route('projects.tasks.toggle', [$project, $task]) }}" class="flex min-w-0 flex-1 items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <button title="{{ $task->status === 'completed' ? 'Kembalikan ke pending' : 'Tandai selesai' }}" class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border {{ $task->status === 'completed' ? 'bg-[#3ba6f1] border-[#3398e1] text-white' : 'border-[#d6d3d1] text-transparent' }}">✓</button>
                                <span class="truncate text-[14px] {{ $task->status === 'completed' ? 'line-through' : 'font-medium' }}">{{ $task->title }}</span>
                            </form>
                            <div class="flex shrink-0 items-center gap-1">
                                <span class="text-[10px] uppercase tracking-wide rounded-full border px-2 py-0.5 {{ $priorityColor }}">{{ $task->priority }}</span>
                                <span class="text-[10px] uppercase tracking-wide rounded-full border border-[#e8e6e5] px-2 py-0.5 text-[#78716c]">{{ $task->status }}</span>
                            </div>
                        </div>
                        @if ($task->description)
                            <p class="mt-1 text-[13px] text-[#78716c]">{{ $task->description }}</p>
                        @endif
                        <p class="mt-1 text-[12px] {{ $isOverdue ? 'text-[#F53003] font-medium' : 'text-[#a8a29e]' }}">
                            {{ $task->deadline ? ($isOverdue ? 'Terlambat: ' : 'Deadline: ') . $task->deadline->format('d M Y H:i') : 'Tanpa deadline' }}
                        </p>
                        <details class="mt-2">
                            <summary class="cursor-pointer text-[13px] text-[#78716c] hover:text-[#0c0a09]">Edit</summary>
                            <form method="POST" action="{{ route('projects.tasks.update', [$project, $task]) }}" class="mt-2 space-y-2">
                                @csrf
                                @method('PUT')
                                <input name="title" value="{{ $task->title }}" required maxlength="255" class="w-full bg-white border border-[#d6d3d1] rounded-[6px] px-3 py-2 text-[14px] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
                                <input name="description" value="{{ $task->description }}" placeholder="Deskripsi..." class="w-full bg-white border border-[#d6d3d1] rounded-[6px] px-3 py-2 text-[14px] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
                                <div class="flex gap-2">
                                    <select name="priority" class="flex-1 bg-white border border-[#d6d3d1] rounded-[6px] px-3 py-2 text-[14px]">
                                        <option value="low" @selected($task->priority === 'low')>Low</option>
                                        <option value="medium" @selected($task->priority === 'medium')>Medium</option>
                                        <option value="high" @selected($task->priority === 'high')>High</option>
                                    </select>
                                    <select name="status" class="flex-1 bg-white border border-[#d6d3d1] rounded-[6px] px-3 py-2 text-[14px]">
                                        <option value="pending" @selected($task->status === 'pending')>Pending</option>
                                        <option value="completed" @selected($task->status === 'completed')>Completed</option>
                                    </select>
                                </div>
                                <input name="deadline" type="datetime-local" value="{{ $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '' }}" class="w-full bg-white border border-[#d6d3d1] rounded-[6px] px-3 py-2 text-[14px]">
                                <button class="w-full px-4 py-1.5 text-[13px] font-medium text-white bg-[#3ba6f1] border border-[#3398e1] rounded-full hover:opacity-90">Simpan</button>
                            </form>
                            <form method="POST" action="{{ route('projects.tasks.destroy', [$project, $task]) }}" onsubmit="return confirm('Hapus tugas ini?')" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button class="w-full px-4 py-1.5 text-[13px] text-[#78716c] hover:text-[#0c0a09] border border-[#e8e6e5] rounded-full">Hapus</button>
                            </form>
                        </details>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
