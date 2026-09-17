<div class="grid gap-5">
    <div>
        <label class="block text-sm font-medium mb-1.5" style="color:#0c0a09;">Nama project</label>
        <input type="text" name="name" required maxlength="255" value="{{ old('name', $project->name) }}" placeholder="cth: Praktikum PPK" class="input-seline">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1.5" style="color:#0c0a09;">Deskripsi <span class="font-normal" style="color:#a8a29e;">(opsional)</span></label>
        <textarea name="description" rows="3" placeholder="Project ini berisi task apa saja…" class="input-seline">{{ old('description', $project->description) }}</textarea>
    </div>
    @if ($users->count() > 0)
    <div>
        <label class="block text-sm font-medium mb-1.5" style="color:#0c0a09;">Owner</label>
        <select name="owner_id" class="input-seline" {{ $project->exists ? 'required' : '' }}>
            @foreach ($users as $u)
                <option value="{{ $u->id }}" {{ (string) old('owner_id', $project->owner_id ?? $users->first()->id) === (string) $u->id ? 'selected' : '' }}>
                    {{ $u->name }} ({{ $u->email }})
                </option>
            @endforeach
        </select>
        <p class="text-xs mt-1.5" style="color:#a8a29e;">Satu project punya tepat satu owner (SRS).</p>
    </div>
    @endif
</div>
