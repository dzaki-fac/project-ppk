<div class="grid gap-5">
    @if (!empty($parentProject))
        {{-- Nested: project dikunci — task PASTI milik project ini --}}
        <div class="rounded-[10px] px-4 py-3 text-sm flex items-center gap-2" style="background:#c1e1f7;color:#0c0a09;">
            <span>📁 Task ini akan menjadi bagian dari project <strong>“{{ $parentProject->name }}”</strong></span>
        </div>
        <input type="hidden" name="project_id" value="{{ $parentProject->id }}">
    @else
        <div>
            <label class="block text-sm font-medium mb-1.5" style="color:#0c0a09;">Project induk <span style="color:#b3261e;">*</span></label>
            <select name="project_id" required class="input-seline">
                @foreach ($projects as $p)
                    <option value="{{ $p->id }}" {{ (string) old('project_id', $task->project_id) === (string) $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
            <p class="text-xs mt-1.5" style="color:#a8a29e;">Setiap task wajib menjadi bagian dari tepat satu project.</p>
        </div>
    @endif

    <div>
        <label class="block text-sm font-medium mb-1.5" style="color:#0c0a09;">Judul task</label>
        <input type="text" name="title" required maxlength="255" value="{{ old('title', $task->title) }}" placeholder="cth: Selesaikan laporan PPK" class="input-seline">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1.5" style="color:#0c0a09;">Deskripsi <span class="font-normal" style="color:#a8a29e;">(opsional)</span></label>
        <textarea name="description" rows="3" placeholder="Detail task…" class="input-seline">{{ old('description', $task->description) }}</textarea>
    </div>

    <div class="grid sm:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1.5" style="color:#0c0a09;">Priority</label>
            <select name="priority" required class="input-seline">
                @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $val => $label)
                    <option value="{{ $val }}" {{ old('priority', $task->priority) === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5" style="color:#0c0a09;">Deadline <span class="font-normal" style="color:#a8a29e;">(opsional)</span></label>
            <input type="datetime-local" name="deadline" value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '') }}" class="input-seline">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5" style="color:#0c0a09;">Status</label>
            <select name="status" required class="input-seline">
                <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
    </div>
</div>
