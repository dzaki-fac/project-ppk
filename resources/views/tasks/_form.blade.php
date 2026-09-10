<div class="flex flex-col gap-4">
    @if (!empty($parentProject))
        {{-- Nested: project dikunci — task PASTI milik project ini --}}
        <div class="rounded-[10px] px-4 py-3 text-sm bg-[#c1e1f7] text-[#0c0a09]">
            <span>📁 Task ini akan menjadi bagian dari project <strong>“{{ $parentProject->name }}”</strong></span>
        </div>
        <input type="hidden" name="project_id" value="{{ $parentProject->id }}">
    @else
        <label class="flex flex-col gap-1 text-sm">
            <span class="text-[#0c0a09] font-medium">Project induk <span class="text-[#78716c]">*</span></span>
            <select name="project_id" required
                class="rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
                @foreach ($projects as $p)
                    <option value="{{ $p->id }}" {{ (string) old('project_id', $task->project_id) === (string) $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
            <span class="text-[12px] text-[#a8a29e]">Setiap task wajib menjadi bagian dari tepat satu project.</span>
            @error('project_id')
                <span class="text-[13px] text-red-600">{{ $message }}</span>
            @enderror
        </label>
    @endif

    <label class="flex flex-col gap-1 text-sm">
        <span class="text-[#0c0a09] font-medium">Judul task <span class="text-[#78716c]">*</span></span>
        <input type="text" name="title" required maxlength="255" value="{{ old('title', $task->title) }}" placeholder="cth: Selesaikan laporan PPK"
            class="rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] placeholder-[#78716c] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
        @error('title')
            <span class="text-[13px] text-red-600">{{ $message }}</span>
        @enderror
    </label>

    <label class="flex flex-col gap-1 text-sm">
        <span class="text-[#0c0a09] font-medium">Deskripsi <span class="font-normal text-[#a8a29e]">(opsional)</span></span>
        <textarea name="description" rows="3" placeholder="Detail task…"
            class="rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] placeholder-[#78716c] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">{{ old('description', $task->description) }}</textarea>
        @error('description')
            <span class="text-[13px] text-red-600">{{ $message }}</span>
        @enderror
    </label>

    <div class="grid sm:grid-cols-3 gap-4">
        <label class="flex flex-col gap-1 text-sm">
            <span class="text-[#0c0a09] font-medium">Priority</span>
            <select name="priority" required
                class="rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
                @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $val => $label)
                    <option value="{{ $val }}" {{ old('priority', $task->priority) === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('priority')
                <span class="text-[13px] text-red-600">{{ $message }}</span>
            @enderror
        </label>
        <label class="flex flex-col gap-1 text-sm">
            <span class="text-[#0c0a09] font-medium">Deadline <span class="font-normal text-[#a8a29e]">(opsional)</span></span>
            <input type="datetime-local" name="deadline" value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '') }}"
                class="rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
            @error('deadline')
                <span class="text-[13px] text-red-600">{{ $message }}</span>
            @enderror
        </label>
        <label class="flex flex-col gap-1 text-sm">
            <span class="text-[#0c0a09] font-medium">Status</span>
            <select name="status" required
                class="rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] focus:outline-none focus:ring-2 focus:ring-[#3ba6f1]">
                <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            @error('status')
                <span class="text-[13px] text-red-600">{{ $message }}</span>
            @enderror
        </label>
    </div>
</div>
