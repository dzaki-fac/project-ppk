<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Global task list (lintas project).
     * Untuk task milik 1 project, gunakan projects.show.
     */
    public function index(Request $request): View
    {
        $parentProject = $this->parentProjectFromRoute($request);

        $query = Task::with('project')->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
            ->orderBy('deadline')
            ->orderByDesc('created_at');

        // Scope: nested /projects/{project}/tasks  ATAU  ?project_id=
        if ($parentProject) {
            $query->where('project_id', $parentProject->id);
        } elseif ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('status') && in_array($request->status, ['pending', 'completed'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority') && in_array($request->priority, ['low', 'medium', 'high'])) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->q.'%')
                    ->orWhere('description', 'like', '%'.$request->q.'%');
            });
        }

        $tasks = $query->paginate(12)->withQueryString();
        $projects = Project::orderBy('name')->get();
        // $project = project yang sedang difilter (untuk dropdown & tombol create)
        $project = $parentProject ?? ($request->filled('project_id') ? Project::find($request->project_id) : null);

        return view('tasks.index', compact('tasks', 'projects', 'project', 'parentProject'));
    }

    /**
     * Form buat task.
     * - Nested: /projects/{project}/tasks/create → project terkunci (hidden input).
     * - Global: /tasks/create → pilih project via dropdown.
     */
    public function create(Request $request): View
    {
        $parentProject = $this->parentProjectFromRoute($request);
        $projects = Project::orderBy('name')->get();

        $preselectedId = $parentProject?->id
            ?? ($request->filled('project_id') ? (int) $request->project_id : $projects->first()?->id);

        $task = new Task([
            'project_id' => $preselectedId,
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        $project = $parentProject ?? Project::find($preselectedId);

        return view('tasks.create', compact('task', 'projects', 'project', 'parentProject'));
    }

    public function store(Request $request): RedirectResponse
    {
        $parentProject = $this->parentProjectFromRoute($request);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
            'status' => 'required|in:pending,completed',
        ]);

        // Kunci: task yang dibuat via nested route tidak boleh pindah project diam-diam
        if ($parentProject && (int) $validated['project_id'] !== $parentProject->id) {
            return back()->withErrors(['project_id' => 'Task ini harus milik project “'.$parentProject->name.'”.'])->withInput();
        }

        $task = Task::create($validated);

        // Kembali ke rumah task: detail project induknya
        return redirect()->route('projects.show', $task->project_id)
            ->with('success', 'Task “'.$task->title.'” berhasil dibuat di project “'.$task->project->name.'”.');
    }

    public function edit(Request $request, Task $task): View
    {
        $parentProject = $this->parentProjectFromRoute($request);
        $projects = Project::orderBy('name')->get();

        // Guard: jika diakses via nested tapi task bukan milik project itu → 404
        if ($parentProject && $task->project_id !== $parentProject->id) {
            abort(404, 'Task bukan bagian dari project ini.');
        }

        return view('tasks.edit', compact('task', 'projects', 'parentProject'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $parentProject = $this->parentProjectFromRoute($request);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
            'status' => 'required|in:pending,completed',
        ]);

        if ($parentProject && (int) $validated['project_id'] !== $parentProject->id) {
            return back()->withErrors(['project_id' => 'Task ini harus tetap di project “'.$parentProject->name.'”.'])->withInput();
        }

        $task->update($validated);

        return redirect()->route('projects.show', $task->project_id)
            ->with('success', 'Task “'.$task->title.'” berhasil diperbarui.');
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        $parentProject = $this->parentProjectFromRoute($request);

        if ($parentProject && $task->project_id !== $parentProject->id) {
            abort(404, 'Task bukan bagian dari project ini.');
        }

        $title = $task->title;
        $projectId = $task->project_id;
        $projectName = $task->project?->name;
        $task->delete();

        // Kembali ke project induk (rumah task), fallback ke global jika project ikut terhapus
        if (Project::where('id', $projectId)->exists()) {
            return redirect()->route('projects.show', $projectId)
                ->with('success', 'Task “'.$title.'” dihapus dari project “'.$projectName.'”.');
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task “'.$title.'” berhasil dihapus.');
    }

    public function toggle(Request $request, Task $task): RedirectResponse
    {
        $parentProject = $this->parentProjectFromRoute($request);

        if ($parentProject && $task->project_id !== $parentProject->id) {
            abort(404, 'Task bukan bagian dari project ini.');
        }

        $task->update([
            'status' => $task->status === 'completed' ? 'pending' : 'completed',
        ]);

        $label = $task->status === 'completed' ? 'selesai' : 'pending';

        return back()->with('success', 'Task “'.$task->title.'” ditandai '.$label.'.');
    }

    /**
     * Ambil parent Project dari nested route /projects/{project}/...
     * Return Project model atau null (untuk route global /tasks/...).
     */
    private function parentProjectFromRoute(Request $request): ?Project
    {
        $param = $request->route('project');

        if ($param instanceof Project) {
            return $param;
        }

        if (is_numeric($param)) {
            return Project::find($param);
        }

        return null;
    }
}
