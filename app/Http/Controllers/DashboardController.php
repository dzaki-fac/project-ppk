<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // Pastikan selalu ada minimal 1 project agar dashboard & CRUD langsung bisa dipakai (demo).
        $project = $this->ensureProject($request);

        $baseQuery = $project ? Task::where('project_id', $project->id) : Task::query();

        $total = (clone $baseQuery)->count();
        $completed = (clone $baseQuery)->where('status', 'completed')->count();
        $pending = $total - $completed;
        $overdue = (clone $baseQuery)->where('status', 'pending')->whereNotNull('deadline')->where('deadline', '<', now())->count();
        $highPriority = (clone $baseQuery)->where('priority', 'high')->where('status', 'pending')->count();

        $progress = $total > 0 ? (int) round($completed / $total * 100) : 0;

        $byPriority = [
            'high' => (clone $baseQuery)->where('priority', 'high')->count(),
            'medium' => (clone $baseQuery)->where('priority', 'medium')->count(),
            'low' => (clone $baseQuery)->where('priority', 'low')->count(),
        ];

        $recentTasks = (clone $baseQuery)->with('project')->latest()->take(6)->get();
        $upcomingTasks = (clone $baseQuery)->where('status', 'pending')->whereNotNull('deadline')->orderBy('deadline')->take(5)->get();

        $projects = Project::withCount(['tasks as completed_tasks_count' => fn ($q) => $q->where('status', 'completed')])->withCount('tasks')->orderBy('name')->get();

        return view('dashboard.index', compact(
            'project', 'projects', 'total', 'completed', 'pending', 'overdue', 'highPriority', 'progress', 'byPriority', 'recentTasks', 'upcomingTasks'
        ));
    }

    private function ensureProject(Request $request): ?Project
    {
        if ($request->filled('project_id')) {
            $found = Project::find($request->project_id);
            if ($found) {
                return $found;
            }
        }

        $project = Project::orderBy('id')->first();

        if (! $project) {
            $user = User::orderBy('id')->first();

            if (! $user) {
                $user = User::create([
                    'name' => 'Demo User',
                    'email' => 'demo@jara.test',
                    'password' => 'password',
                    'role' => 'user',
                ]);
            }

            $project = Project::create([
                'owner_id' => $user->id,
                'name' => 'My First Project',
                'description' => 'Project contoh otomatis — silakan ubah atau tambah task baru.',
            ]);

            // Owner otomatis jadi member juga
            $project->members()->syncWithoutDetaching([$user->id]);
        }

        return $project;
    }
}
