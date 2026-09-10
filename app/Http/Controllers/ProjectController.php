<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $owned = Project::where('owner_id', $user->id)->withCount(['members', 'tasks'])->latest()->get();
        $memberOf = $user->projects()->withCount(['members', 'tasks'])->get();

        $memberIds = $memberOf->pluck('id')->all();
        $owned = $owned->reject(fn ($p) => in_array($p->id, $memberIds));

        return view('projects.index', compact('owned', 'memberOf'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project = Project::create([
            ...$data,
            'owner_id' => Auth::id(),
        ]);

        return redirect()->route('projects.show', $project)->with('status', 'Project berhasil dibuat.');
    }

    public function show(Project $project)
    {
        $this->authorizeAccess($project);

        $project->load(['owner', 'members']);
        $project->load(['tasks' => fn ($q) => $q->orderByRaw("CASE status WHEN 'pending' THEN 0 ELSE 1 END")->orderByRaw("CASE priority WHEN 'high' THEN 0 WHEN 'medium' THEN 1 ELSE 2 END")->orderBy('deadline')->latest()]);

        $total = $project->tasks->count();
        $done = $project->tasks->where('status', 'completed')->count();
        $progress = $total > 0 ? (int) round($done / $total * 100) : 0;

        return view('projects.show', compact('project', 'total', 'done', 'progress'));
    }

    public function edit(Project $project)
    {
        $this->authorizeOwner($project);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorizeOwner($project);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project->update($data);

        return redirect()->route('projects.show', $project)->with('status', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        $this->authorizeOwner($project);

        $project->delete();

        return redirect()->route('projects.index')->with('status', 'Project berhasil dihapus.');
    }

    private function authorizeOwner(Project $project): void
    {
        if ($project->owner_id !== Auth::id()) {
            abort(403, 'Hanya owner yang dapat mengelola project ini.');
        }
    }

    private function authorizeAccess(Project $project): void
    {
        $userId = Auth::id();

        if ($project->owner_id === $userId) {
            return;
        }

        if ($project->members()->where('user_id', $userId)->exists()) {
            return;
        }

        abort(403, 'Anda tidak memiliki akses ke project ini.');
    }
}
