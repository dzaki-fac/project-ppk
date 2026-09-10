<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $this->authorizeAccess($project);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high'],
            'deadline' => ['nullable', 'date'],
        ]);

        $project->tasks()->create($data);

        return back()->with('status', 'Tugas berhasil ditambahkan.');
    }

    public function update(Request $request, Project $project, Task $task)
    {
        $this->authorizeAccess($project);
        $this->ensureTaskInProject($project, $task);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high'],
            'deadline' => ['nullable', 'date'],
            'status' => ['required', 'in:pending,completed'],
        ]);

        $task->update($data);

        return back()->with('status', 'Tugas berhasil diperbarui.');
    }

    public function toggle(Project $project, Task $task)
    {
        $this->authorizeAccess($project);
        $this->ensureTaskInProject($project, $task);

        $task->update([
            'status' => $task->status === 'completed' ? 'pending' : 'completed',
        ]);

        return back()->with('status', $task->status === 'completed' ? 'Tugas ditandai selesai.' : 'Tugas dikembalikan ke pending.');
    }

    public function destroy(Project $project, Task $task)
    {
        $this->authorizeAccess($project);
        $this->ensureTaskInProject($project, $task);

        $task->delete();

        return back()->with('status', 'Tugas berhasil dihapus.');
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

    private function ensureTaskInProject(Project $project, Task $task): void
    {
        if ($task->project_id !== $project->id) {
            abort(404);
        }
    }
}
