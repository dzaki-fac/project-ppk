@extends('layouts.app')

@section('title', 'Edit task - '.config('app.name', 'Jara'))

@section('content')
<div class="bg-[#fafaf9]">
    <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-xl">
            <p class="text-[14px] text-[#78716c]">
                <a href="{{ route('projects.index') }}" class="hover:text-[#0c0a09]">Projects</a>
                <span class="mx-1">›</span>
                <a href="{{ route('projects.show', $task->project_id) }}" class="hover:text-[#0c0a09]">{{ $task->project?->name ?? 'Project' }}</a>
                <span class="mx-1">›</span><span class="text-[#0c0a09]">Edit task</span>
            </p>
            <h1 class="mt-2 text-[32px] leading-[1.25] tracking-[-0.8px] font-normal text-[#0c0a09]" style="font-family:'Inter Tight','Roobert',ui-sans-serif,system-ui,sans-serif;">
                Perbarui <span class="text-[#3398e1] bg-[#c1e1f7] rounded px-2 py-0.5">task</span>
            </h1>
        </div>

        <div class="mt-8 max-w-xl rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
            <form method="POST" action="{{ route('tasks.update', $task) }}" class="flex flex-col gap-4">
                @csrf @method('PUT')
                @include('tasks._form')
                <div class="mt-2 flex items-center gap-2">
                    <button type="submit" class="rounded-full bg-[#3ba6f1] border border-[#3398e1] text-white text-sm font-medium px-4 py-2 hover:opacity-90 transition">Simpan perubahan</button>
                    <a href="{{ route('projects.show', $task->project_id) }}" class="rounded-full border border-[#e8e6e5] px-4 py-2 text-sm text-[#0c0a09] hover:bg-[#fafaf9] transition">Kembali ke project</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
