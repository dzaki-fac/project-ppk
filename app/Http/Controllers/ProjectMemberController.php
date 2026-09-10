<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProjectMemberController extends Controller
{
    /**
     * Display project members.
     * Accessible by owner or member (policy: viewMembers).
     */
    public function index(Request $request, Project $project): View
    {
        Gate::authorize('viewMembers', $project);

        $project->load(['owner', 'members']);

        $isOwner = $project->owner_id === $request->user()->id;

        return view('projects.members.index', compact('project', 'isOwner'));
    }

    /**
     * Add a user as project member (owner only).
     */
    public function store(Request $request, Project $project): RedirectResponse
    {
        Gate::authorize('addMember', $project);

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        // Owner cannot add themselves as member (already has access).
        if ($user->id === $project->owner_id) {
            return back()->withErrors([
                'email' => 'Owner sudah memiliki akses penuh, tidak perlu ditambahkan sebagai member.',
            ])->withInput();
        }

        // Prevent duplicate membership (also guarded by unique DB constraint).
        if ($project->members()->where('users.id', $user->id)->exists()) {
            return back()->withErrors([
                'email' => 'User tersebut sudah menjadi member project ini.',
            ])->withInput();
        }

        $project->members()->attach($user->id);

        return back()->with('status', "Member {$user->name} berhasil ditambahkan.");
    }

    /**
     * Remove a member from the project (owner only).
     */
    public function destroy(Request $request, Project $project, User $user): RedirectResponse
    {
        Gate::authorize('removeMember', $project);

        // Owner record itself is not in pivot; guard just in case.
        if ($user->id === $project->owner_id) {
            return back()->withErrors([
                'member' => 'Owner tidak dapat dihapus dari project.',
            ]);
        }

        if (! $project->members()->where('users.id', $user->id)->exists()) {
            return back()->withErrors([
                'member' => 'User tersebut bukan member project ini.',
            ]);
        }

        $project->members()->detach($user->id);

        return back()->with('status', "Member {$user->name} berhasil dihapus.");
    }
}
