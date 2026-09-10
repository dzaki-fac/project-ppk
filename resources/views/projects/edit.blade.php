@extends('layouts.app')

@section('title', 'Edit ' . $project->name)

@section('content')
<div class="mx-auto max-w-[640px]">
    <h1 class="font-display text-[32px] leading-[1.25] font-normal">Edit <span class="text-[#3398e1] bg-[#c1e1f7] px-2 py-0.5 rounded">project</span></h1>
    <p class="mt-2 text-[14px] text-[#78716c]">Hanya owner yang dapat mengubah project ini.</p>

    <div class="mt-6 bg-white border border-[#e8e6e5] rounded-[10px] p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
        <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[13px] font-medium mb-1.5" for="name">Nama project</label>
                <input id="name" name="name" value="{{ old('name', $project->name) }}" required maxlength="255" class="w-full bg-white border border-[#d6d3d1] rounded-[6px] px-3 py-2 text-[14px] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
            </div>
            <div>
                <label class="block text-[13px] font-medium mb-1.5" for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="w-full bg-white border border-[#d6d3d1] rounded-[6px] px-3 py-2 text-[14px] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">{{ old('description', $project->description) }}</textarea>
            </div>
            <div class="flex items-center gap-2 pt-2">
                <button type="submit" class="px-4 py-2 text-[14px] font-medium text-white bg-[#3ba6f1] border border-[#3398e1] rounded-full hover:opacity-90">Simpan perubahan</button>
                <a href="{{ route('projects.show', $project) }}" class="px-4 py-2 text-[14px] text-[#0c0a09] border border-[#e8e6e5] rounded-full">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
