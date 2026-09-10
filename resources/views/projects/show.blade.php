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
                    <h3 class="text-[16px] font-medium text-[#0c0a09]">Progress</h3>
                    <div class="mt-3 flex items-center gap-3">
                        <div class="flex-1 h-2 rounded-full overflow-hidden bg-[#e8e6e5]">
                            <div class="h-full rounded-full bg-[#3ba6f1]" style="width:{{ $progress ?? 0 }}%;"></div>
                        </div>
                        <span class="rounded-full px-2.5 py-0.5 text-[12px] font-medium bg-[#c1e1f7] text-[#0c0a09]">{{ $progress ?? 0 }}%</span>
                    </div>
                    <p class="mt-3 text-[13px] leading-[1.64] text-[#78716c]">
                        <span class="font-medium text-[#0c0a09]">{{ $completed ?? 0 }}</span> selesai &middot;
                        <span class="font-medium text-[#0c0a09]">{{ $pending ?? 0 }}</span> pending &middot;
                        <span class="font-medium text-[#0c0a09]">{{ $overdue ?? 0 }}</span> overdue
                        dari <span class="font-medium text-[#0c0a09]">{{ $total ?? 0 }}</span> total
                    </p>
                    <a href="{{ route('projects.tasks.create', $project) }}" class="mt-4 inline-flex items-center rounded-full bg-[#3ba6f1] border border-[#3398e1] text-white text-sm font-medium px-4 py-2 hover:opacity-90 transition">+ Task di project ini</a>
                </div>
            </div>
        </div>

        {{-- Tasks milik project ini --}}
        <div class="mt-6 rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]" style="font-family:'Inter Tight','Roobert',ui-sans-serif,system-ui,sans-serif;">
                    Tasks ({{ $tasks->total() ?? 0 }})
                </h2>
                <a href="{{ route('tasks.index') }}" class="text-[13px] text-[#3398e1] hover:underline">Lihat semua task lintas project &rarr;</a>
            </div>

            <form method="GET" action="{{ route('projects.show', $project) }}" class="mt-4 flex flex-wrap gap-3 items-end">
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
            </form>

            <div class="mt-4 divide-y divide-[#e8e6e5] border-t border-[#e8e6e5]">
                @forelse ($tasks as $task)
                    <div class="flex items-start gap-4 py-4">
                        <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="pt-1 shrink-0">
                            @csrf @method('PATCH')
                            <button title="Tandai selesai / pending" class="flex h-5 w-5 items-center justify-center rounded-full text-white"
                                style="border:1px solid {{ $task->status === 'completed' ? '#3ba6f1' : '#d6d3d1' }};background:{{ $task->status === 'completed' ? '#3ba6f1' : '#fff' }};">
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
                                        @if ($task->status === 'pending' && $task->deadline->isPast()) &middot; Overdue @endif
                                    </span>
                                @endif
                            </div>
                            <p class="font-medium text-[#0c0a09]" style="{{ $task->status === 'completed' ? 'text-decoration:line-through;opacity:.6;' : '' }}">{{ $task->title }}</p>
                            @if ($task->description)
                                <p class="text-sm mt-1 line-clamp-2 text-[#78716c]">{{ $task->description }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('tasks.edit', $task) }}" class="text-xs font-medium px-3 py-1.5 rounded-full border border-[#e8e6e5] text-[#0c0a09] hover:bg-[#fafaf9]">Edit</a>
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Hapus task “{{ addslashes($task->title) }}” dari project ini?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-medium px-3 py-1.5 rounded-full border border-[#e8e6e5] text-[#78716c] hover:bg-[#fafaf9]">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center">
                        <p class="text-[16px] font-medium text-[#0c0a09]">Project ini belum punya task</p>
                        <p class="mt-1 text-[14px] text-[#78716c]">Tambahkan task pertama ke “{{ $project->name }}”.</p>
                        <a href="{{ route('projects.tasks.create', $project) }}" class="mt-4 inline-flex items-center rounded-full bg-[#3ba6f1] border border-[#3398e1] text-white text-sm font-medium px-4 py-2 hover:opacity-90 transition">+ Tambah task di sini</a>
                    </div>
                @endforelse
            </div>

            @if (method_exists($tasks, 'links'))
                <div class="mt-4">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
