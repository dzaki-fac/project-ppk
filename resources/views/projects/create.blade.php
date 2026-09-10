@extends('layouts.app')

@section('title', 'Buat Project - ' . config('app.name', 'Jara'))

@section('content')
<div class="bg-[#fafaf9]">
    <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-xl">
            <h1 class="text-[32px] leading-[1.25] tracking-[-0.8px] font-normal text-[#0c0a09]" style="font-family:'Inter Tight','Roobert',ui-sans-serif,system-ui,sans-serif;">
                Buat project <span class="text-[#3398e1] bg-[#c1e1f7] rounded px-2 py-0.5">baru</span>
            </h1>
            <p class="mt-2 text-[14px] leading-[1.64] text-[#78716c]">Isi nama dan deskripsi singkat agar tim memahami tujuan project.</p>
        </div>

        <div class="mt-8 max-w-xl rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
            <form method="POST" action="{{ route('projects.store') }}" class="flex flex-col gap-4">
                @csrf

                <label class="flex flex-col gap-1 text-sm">
                    <span class="text-[#0c0a09] font-medium">Nama project <span class="text-[#78716c]">*</span></span>
                    <input type="text" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="cth: Website Jara"
                        class="rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] placeholder-[#78716c] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
                    @error('name')
                        <span class="text-[13px] text-red-600">{{ $message }}</span>
                    @enderror
                </label>

                <label class="flex flex-col gap-1 text-sm">
                    <span class="text-[#0c0a09] font-medium">Deskripsi</span>
                    <textarea name="description" rows="4" placeholder="Tujuan dan ruang lingkup project (opsional)"
                        class="rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] placeholder-[#78716c] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-[13px] text-red-600">{{ $message }}</span>
                    @enderror
                </label>

                <div class="mt-2 flex items-center gap-2">
                    <button type="submit" class="rounded-full bg-[#3ba6f1] border border-[#3398e1] text-white text-sm font-medium px-4 py-2 hover:opacity-90 transition">Simpan project</button>
                    <a href="{{ route('projects.index') }}" class="rounded-full border border-[#e8e6e5] px-4 py-2 text-sm text-[#0c0a09] hover:bg-[#fafaf9] transition">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
