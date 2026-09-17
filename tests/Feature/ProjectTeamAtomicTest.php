<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * P2 — FR-12 + FR-13 (branch feature/project-team).
 *
 * File baru (tidak mengubah ProjectTeamTest.php) agar merge bersih:
 * - FR-12: store atomik — create project + daftarkan owner jadi member
 *          dalam satu DB::transaction eksplisit.
 * - FR-13: destroy atomik + cascade — hapus project beserta tasks
 *          dan memberships, non-owner ditolak 403.
 */
class ProjectTeamAtomicTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_project_and_registers_owner_as_member(): void
    {
        $owner = User::factory()->create();

        $this->actingAs($owner)->post(route('projects.store'), [
            'name' => 'Atomic Project',
            'description' => 'Dibuat atomik',
        ])->assertRedirect();

        $project = Project::where('name', 'Atomic Project')->first();
        $this->assertNotNull($project);
        $this->assertEquals($owner->id, $project->owner_id);

        // Owner otomatis terdaftar di pivot project_members.
        $this->assertTrue(
            $project->members()->where('users.id', $owner->id)->exists(),
            'Owner harus otomatis menjadi member setelah store.'
        );

        // Hanya satu baris pivot untuk owner (tidak duplikat).
        $this->assertEquals(
            1,
            DB::table('project_members')
                ->where('project_id', $project->id)
                ->where('user_id', $owner->id)
                ->count()
        );
    }

    public function test_store_validation_failure_creates_nothing(): void
    {
        $owner = User::factory()->create();

        $this->actingAs($owner)->post(route('projects.store'), [
            'name' => '',
        ])->assertSessionHasErrors('name');

        // Atomik: tidak ada orphan project maupun pivot saat validasi gagal.
        $this->assertEquals(0, Project::count());
        $this->assertEquals(0, DB::table('project_members')->count());
    }

    public function test_destroy_cascades_tasks_and_memberships(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        // Buat via HTTP agar owner otomatis jadi member (FR-12).
        $this->actingAs($owner)->post(route('projects.store'), [
            'name' => 'Cascade Target',
            'description' => null,
        ])->assertRedirect();

        $project = Project::where('name', 'Cascade Target')->firstOrFail();
        $projectId = $project->id;

        // Tambah 1 member + 2 tasks.
        $project->members()->syncWithoutDetaching([$member->id]);
        Task::create(['project_id' => $projectId, 'title' => 'T1']);
        Task::create(['project_id' => $projectId, 'title' => 'T2', 'priority' => 'high']);

        $this->assertEquals(2, DB::table('project_members')->where('project_id', $projectId)->count());
        $this->assertEquals(2, Task::where('project_id', $projectId)->count());

        // Owner hapus → atomik + cascade.
        $this->actingAs($owner)
            ->delete(route('projects.destroy', $project))
            ->assertRedirect();

        $this->assertNull(Project::find($projectId));
        $this->assertEquals(0, DB::table('project_members')->where('project_id', $projectId)->count());
        $this->assertEquals(0, Task::where('project_id', $projectId)->count());
    }

    public function test_non_owner_cannot_delete_project(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();

        $project = Project::create([
            'owner_id' => $owner->id,
            'name' => 'Protected',
            'description' => null,
        ]);

        $this->actingAs($stranger)
            ->delete(route('projects.destroy', $project))
            ->assertForbidden();

        // Project tetap ada (tidak terhapus oleh non-owner).
        $this->assertNotNull(Project::find($project->id));
    }
}
