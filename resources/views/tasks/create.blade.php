@extends('layouts.app')

@section('title', isset($parentProject) && $parentProject ? 'Buat task di '.$parentProject->name.' — Jara' : 'Buat task — Jara')

@section('content')
<div class="max-w-[640px] mx-auto">
    <p class="text-xs mb-3" style="color:#a8a29e;">
        @if (!empty($parentProject))
            <a href="{{ route('projects.index') }}" class="hover:underline" style="color:#3398e1;">Projects</a>
            <span class="mx-1">›</span>
            <a href="{{ route('projects.show', $parentProject) }}" class="hover:underline" style="color:#3398e1;">{{ $parentProject->name }}</a>
            <span class="mx-1">›</span><span class="font-medium" style="color:#0c0a09;">Task baru</span>
        @else
            <a href="{{ route('tasks.index') }}" class="hover:underline" style="color:#3398e1;">Tasks</a>
            <span class="mx-1">›</span><span class="font-medium" style="color:#0c0a09;">Baru</span>
        @endif
    </p>
    <h1 class="font-display mb-6" style="font-size:32px;line-height:1.25;letter-spacing:-0.8px;color:#0c0a09;">
        @if (!empty($parentProject))
            Task baru di <span class="highlight-span">{{ $parentProject->name }}</span>
        @else
            Buat task <span class="highlight-span">baru</span>
        @endif
    </h1>

    <form method="POST" action="{{ !empty($parentProject) ? route('projects.tasks.store', $parentProject) : route('tasks.store') }}" class="card-flat p-6">
        @csrf
        @include('tasks._form')
        <div class="mt-6 flex items-center gap-3">
            <button class="btn-primary">Simpan task</button>
            @if (!empty($parentProject))
                <a href="{{ route('projects.show', $parentProject) }}" class="btn-ghost">Batal</a>
            @else
                <a href="{{ route('tasks.index', ['project_id' => $project?->id]) }}" class="btn-ghost">Batal</a>
            @endif
        </div>
    </form>
</div>
@endsection
