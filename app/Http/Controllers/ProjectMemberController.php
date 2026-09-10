<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectMemberController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $this->authorizeOwner($project);

        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if ($user->id === $project->owner_id) {
            return back()->withErrors(['email' => 'Owner sudah menjadi bagian dari project.']);
        }

        if ($project->members()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['email' => 'User sudah menjadi anggota project ini.']);
        }

        $project->members()->attach($user->id);

        return back()->with('status', "Anggota {$user->name} berhasil ditambahkan.");
    }

    public function destroy(Project $project, User $user)
    {
        $this->authorizeOwner($project);

        if ($user->id === $project->owner_id) {
            return back()->withErrors(['email' => 'Owner tidak dapat dihapus dari project.']);
        }

        $project->members()->detach($user->id);

        return back()->with('status', "Anggota {$user->name} berhasil dihapus.");
    }

    private function authorizeOwner(Project $project): void
    {
        if ($project->owner_id !== Auth::id()) {
            abort(403, 'Hanya owner yang dapat mengelola anggota.');
        }
    }
}
