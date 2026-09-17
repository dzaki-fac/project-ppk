<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display owned projects and followed (member) projects.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $ownedProjects = Project::where('owner_id', $user->id)
            ->with(['owner', 'members'])
            ->withCount('members')
            ->latest()
            ->get();

        $memberProjects = $user->projects()
            ->with(['owner', 'members'])
            ->withCount('members')
            ->latest('projects.created_at')
            ->get();

        return view('projects.index', compact('ownedProjects', 'memberProjects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(): View
    {
        Gate::authorize('create', Project::class);

        return view('projects.create');
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Project::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project = Project::create([
            'owner_id' => $request->user()->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Project berhasil dibuat.');
    }

    /**
     * Display the specified project (owner or member only).
     */
    public function show(Request $request, Project $project): View
    {
        Gate::authorize('view', $project);

        $project->load(['owner', 'members']);

        $isOwner = $project->owner_id === $request->user()->id;

        $tasksQuery = $project->tasks()
            ->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
            ->orderBy('deadline')
            ->orderByDesc('created_at');

        if ($request->filled('status') && in_array($request->status, ['pending', 'completed'])) {
            $tasksQuery->where('status', $request->status);
        }

        if ($request->filled('priority') && in_array($request->priority, ['low', 'medium', 'high'])) {
            $tasksQuery->where('priority', $request->priority);
        }

        $tasks = $tasksQuery->paginate(10)->withQueryString();

        $total = $project->tasks()->count();
        $completed = $project->tasks()->where('status', 'completed')->count();
        $pending = $total - $completed;
        $overdue = $project->tasks()->where('status', 'pending')->whereNotNull('deadline')->where('deadline', '<', now())->count();
        $progress = $total > 0 ? (int) round($completed / $total * 100) : 0;

        return view('projects.show', compact('project', 'isOwner', 'tasks', 'total', 'completed', 'pending', 'overdue', 'progress'));
    }

    /**
     * Show the form for editing the project (owner only).
     */
    public function edit(Request $request, Project $project): View
    {
        Gate::authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the project (owner only).
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project->update($validated);

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Project berhasil diperbarui.');
    }

    /**
     * Remove the project (owner only).
     */
    public function destroy(Request $request, Project $project): RedirectResponse
    {
        Gate::authorize('delete', $project);

        // SRS-03: hapus project + tasks + memberships secara atomik.
        DB::transaction(function () use ($project) {
            $project->members()->detach();
            $project->delete();
        });

        return redirect()
            ->route('projects.index')
            ->with('status', 'Project berhasil dihapus.');
    }
}
