<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine whether the user can view the project list.
     * Any authenticated user may see the index (controller filters
     * owned vs. followed projects).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the project.
     * Owner or member may view detail.
     */
    public function view(User $user, Project $project): bool
    {
        if ($project->owner_id === $user->id) {
            return true;
        }

        return $project->members()->where('users.id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create projects.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the owner may update the project.
     */
    public function update(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }

    /**
     * Only the owner may delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }

    /**
     * Owner or member may see the member list.
     * ("Hanya owner" restriction applies to add/remove, not viewing.)
     */
    public function viewMembers(User $user, Project $project): bool
    {
        return $this->view($user, $project);
    }

    /**
     * Only the owner may add members.
     */
    public function addMember(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }

    /**
     * Only the owner may remove members.
     */
    public function removeMember(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }
}
