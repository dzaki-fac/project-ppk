<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::with('owner')
            ->withCount('tasks')
            ->withCount(['tasks as completed_tasks_count' => fn ($q) => $q->where('status', 'completed')])
            ->withCount(['tasks as pending_tasks_count' => fn ($q) => $q->where('status', 'pending')])
            ->orderBy('name')
            ->paginate(12);

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        $project = new Project();
        $users = User::orderBy('name')->get();

        return view('projects.create', compact('project', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'owner_id' => 'nullable|exists:users,id',
        ]);

        $validated['owner_id'] ??= $this->defaultOwnerId();

        $project = Project::create($validated);
        $project->members()->syncWithoutDetaching([$project->owner_id]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project “'.$project->name.'” berhasil dibuat.');
    }

    public function show(Request $request, Project $project): View
    {
        $project->load(['owner', 'members']);

        $tasksQuery = $project->tasks()->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
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

        return view('projects.show', compact('project', 'tasks', 'total', 'completed', 'pending', 'overdue', 'progress'));
    }

    public function edit(Project $project): View
    {
        $users = User::orderBy('name')->get();

        return view('projects.edit', compact('project', 'users'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'owner_id' => 'required|exists:users,id',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project “'.$project->name.'” berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $name = $project->name;
        $project->delete(); // tasks ikut terhapus via cascadeOnDelete

        return redirect()->route('projects.index')
            ->with('success', 'Project “'.$name.'” beserta semua task-nya berhasil dihapus.');
    }

    private function defaultOwnerId(): int
    {
        $user = User::orderBy('id')->first();

        if (! $user) {
            $user = User::create([
                'name' => 'Demo User',
                'email' => 'demo@jara.test',
                'password' => 'password',
                'role' => 'user',
            ]);
        }

        return $user->id;
    }
}
