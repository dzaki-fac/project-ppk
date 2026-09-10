<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_crud_scoped_to_owner(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();

        // Create
        $this->actingAs($owner)->post(route('projects.store'), [
            'name' => 'Alpha',
            'description' => 'Desc A',
        ])->assertRedirect();
        $project = Project::where('name', 'Alpha')->first();
        $this->assertNotNull($project);
        $this->assertEquals($owner->id, $project->owner_id);

        // Index memisahkan milik sendiri vs. diikuti
        $this->actingAs($owner)->get(route('projects.index'))->assertOk()->assertSee('Alpha');

        // Create & edit pages render untuk user berhak
        $this->actingAs($owner)->get(route('projects.create'))->assertOk();
        $this->actingAs($owner)->get(route('projects.edit', $project))->assertOk();

        // Stranger: tidak bisa lihat / ubah / hapus
        $this->actingAs($stranger)->get(route('projects.show', $project))->assertForbidden();
        $this->actingAs($stranger)->get(route('projects.edit', $project))->assertForbidden();
        $this->actingAs($stranger)->put(route('projects.update', $project), ['name' => 'X'])->assertForbidden();
        $this->actingAs($stranger)->delete(route('projects.destroy', $project))->assertForbidden();

        // Owner update
        $this->actingAs($owner)->put(route('projects.update', $project), [
            'name' => 'Alpha 2',
            'description' => 'D2',
        ])->assertRedirect();
        $this->assertEquals('Alpha 2', $project->fresh()->name);

        // Validasi: nama wajib
        $this->actingAs($owner)->post(route('projects.store'), ['name' => ''])->assertSessionHasErrors('name');

        // Owner delete
        $this->actingAs($owner)->delete(route('projects.destroy', $project))->assertRedirect();
        $this->assertNull(Project::find($project->id));
    }

    public function test_member_management_owner_only(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $stranger = User::factory()->create();
        $project = Project::create(['owner_id' => $owner->id, 'name' => 'Beta', 'description' => null]);

        // Owner tambah member via email
        $this->actingAs($owner)->post(route('projects.members.store', $project), [
            'email' => $member->email,
        ])->assertRedirect();
        $this->assertTrue($project->fresh()->members()->where('users.id', $member->id)->exists());

        // Member kini bisa lihat project + daftar member
        $this->actingAs($member)->get(route('projects.show', $project))->assertOk();
        $this->actingAs($member)->get(route('projects.members.index', $project))->assertOk();
        $this->actingAs($member)->get(route('projects.index'))->assertOk()->assertSee('Beta');

        // Duplikat ditolak (bukan 500)
        $this->actingAs($owner)->post(route('projects.members.store', $project), [
            'email' => $member->email,
        ])->assertSessionHasErrors('email');

        // Owner tidak bisa menambahkan dirinya sendiri
        $this->actingAs($owner)->post(route('projects.members.store', $project), [
            'email' => $owner->email,
        ])->assertSessionHasErrors('email');

        // Bukan owner: tidak bisa tambah / hapus member, tidak bisa lihat project
        $this->actingAs($member)->post(route('projects.members.store', $project), [
            'email' => $stranger->email,
        ])->assertForbidden();
        $this->actingAs($member)->delete(route('projects.members.destroy', [$project, $member]))->assertForbidden();
        $this->actingAs($stranger)->get(route('projects.members.index', $project))->assertForbidden();

        // Owner hapus member
        $this->actingAs($owner)->delete(route('projects.members.destroy', [$project, $member]))->assertRedirect();
        $this->assertFalse($project->fresh()->members()->where('users.id', $member->id)->exists());

        // Mantan member tidak bisa lihat lagi
        $this->actingAs($member)->get(route('projects.show', $project))->assertForbidden();
    }

    public function test_guest_cannot_access_projects_api(): void
    {
        // Tanpa login: request JSON ditolak 401 (tanpa tergantung route login milik Programmer Auth).
        $this->getJson(route('projects.index'))->assertUnauthorized();
    }
}
