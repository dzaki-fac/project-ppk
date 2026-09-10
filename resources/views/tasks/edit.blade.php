@extends('layouts.app')

@section('title', 'Edit task — Jara')

@section('content')
<div class="max-w-[640px] mx-auto">
    <p class="text-xs mb-3" style="color:#a8a29e;">
        <a href="{{ route('projects.index') }}" class="hover:underline" style="color:#3398e1;">Projects</a>
        <span class="mx-1">›</span>
        <a href="{{ route('projects.show', $task->project_id) }}" class="hover:underline" style="color:#3398e1;">{{ $task->project?->name ?? 'Project' }}</a>
        <span class="mx-1">›</span><span class="font-medium" style="color:#0c0a09;">Edit task</span>
    </p>
    <h1 class="font-display mb-6" style="font-size:32px;line-height:1.25;letter-spacing:-0.8px;color:#0c0a09;">
        Perbarui <span class="highlight-span">task</span>
    </h1>

    <form method="POST" action="{{ route('tasks.update', $task) }}" class="card-flat p-6">
        @csrf @method('PUT')
        @include('tasks._form')
        <div class="mt-6 flex items-center gap-3">
            <button class="btn-primary">Simpan perubahan</button>
            <a href="{{ route('projects.show', $task->project_id) }}" class="btn-ghost">Kembali ke project</a>
        </div>
    </form>
</div>
@endsection
