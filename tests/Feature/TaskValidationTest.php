<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * FR-14 (domain task) — P3 feature/task-progress.
 *
 * Penegasan:
 * - whitelist status (pending/completed) & priority (low/medium/high)
 * - validasi title/description/deadline/project_id
 * - audit: tidak ada raw SQL dari input user (semua via Eloquent binding)
 */
class TaskValidationTest extends TestCase
{
    use RefreshDatabase;

    private function makeProject(User $owner, string $name = 'Proyek A'): Project
    {
        return Project::create([
            'owner_id' => $owner->id,
            'name' => $name,
            'description' => 'desc',
        ]);
    }

    private function validPayload(Project $project): array
    {
        return [
            'project_id' => $project->id,
            'title' => 'Kerjakan laporan',
            'description' => 'Detail laporan PPK',
            'priority' => 'medium',
            'deadline' => '2026-12-01 10:00:00',
            'status' => 'pending',
        ];
    }

    // ── Validasi title ──────────────────────────────────────────

    public function test_store_rejects_missing_and_overlong_title(): void
    {
        $owner = User::factory()->create();
        $project = $this->makeProject($owner);

        // title wajib
        $this->actingAs($owner)
            ->post(route('tasks.store'), array_merge($this->validPayload($project), ['title' => '']))
            ->assertSessionHasErrors('title');

        $this->actingAs($owner)
            ->post(route('tasks.store'), array_merge($this->validPayload($project), ['title' => null]))
            ->assertSessionHasErrors('title');

        // title > 255 char ditolak
        $this->actingAs($owner)
            ->post(route('tasks.store'), array_merge($this->validPayload($project), ['title' => str_repeat('a', 256)]))
            ->assertSessionHasErrors('title');

        $this->assertSame(0, Task::count());
    }

    // ── Validasi description & deadline ─────────────────────────

    public function test_store_validates_description_and_deadline(): void
    {
        $owner = User::factory()->create();
        $project = $this->makeProject($owner);

        // description harus string bila ada (array ditolak)
        $this->actingAs($owner)
            ->post(route('tasks.store'), array_merge($this->validPayload($project), ['description' => ['bukan', 'string']]))
            ->assertSessionHasErrors('description');

        // deadline harus tanggal valid
        $this->actingAs($owner)
            ->post(route('tasks.store'), array_merge($this->validPayload($project), ['deadline' => 'bukan-tanggal']))
            ->assertSessionHasErrors('deadline');

        // description & deadline boleh null/kosong
        $this->actingAs($owner)
            ->post(route('tasks.store'), array_merge($this->validPayload($project), [
                'description' => null,
                'deadline' => null,
            ]))
            ->assertRedirect(route('projects.show', $project->id));

        $this->assertSame(1, Task::count());
    }

    // ── Whitelist status/priority + project_id ──────────────────

    public function test_store_rejects_invalid_status_priority_and_project(): void
    {
        $owner = User::factory()->create();
        $project = $this->makeProject($owner);

        foreach (['urgent', 'done', 'PENDING', "pending' OR '1'='1", 'low; DROP TABLE tasks'] as $badStatus) {
            $this->actingAs($owner)
                ->post(route('tasks.store'), array_merge($this->validPayload($project), ['status' => $badStatus]))
                ->assertSessionHasErrors('status');
        }

        foreach (['critical', 'MEDIUM', "high' OR '1'='1", 'low,high'] as $badPriority) {
            $this->actingAs($owner)
                ->post(route('tasks.store'), array_merge($this->validPayload($project), ['priority' => $badPriority]))
                ->assertSessionHasErrors('priority');
        }

        // project_id wajib & harus ada
        $this->actingAs($owner)
            ->post(route('tasks.store'), array_merge($this->validPayload($project), ['project_id' => '']))
            ->assertSessionHasErrors('project_id');

        $this->actingAs($owner)
            ->post(route('tasks.store'), array_merge($this->validPayload($project), ['project_id' => 999999]))
            ->assertSessionHasErrors('project_id');

        $this->assertSame(0, Task::count());
    }

    public function test_update_applies_same_whitelist_and_validation(): void
    {
        $owner = User::factory()->create();
        $project = $this->makeProject($owner);
        $task = Task::create($this->validPayload($project));

        // status/priority invalid ditolak saat update
        $this->actingAs($owner)
            ->put(route('tasks.update', $task), array_merge($this->validPayload($project), ['status' => 'hacked']))
            ->assertSessionHasErrors('status');

        $this->actingAs($owner)
            ->put(route('tasks.update', $task), array_merge($this->validPayload($project), ['priority' => 'hacked']))
            ->assertSessionHasErrors('priority');

        $this->actingAs($owner)
            ->put(route('tasks.update', $task), array_merge($this->validPayload($project), ['title' => '']))
            ->assertSessionHasErrors('title');

        $this->actingAs($owner)
            ->put(route('tasks.update', $task), array_merge($this->validPayload($project), ['deadline' => 'xxx']))
            ->assertSessionHasErrors('deadline');

        // nilai asli tidak berubah oleh request invalid
        $this->assertSame('pending', $task->fresh()->status);
        $this->assertSame('medium', $task->fresh()->priority);
    }

    // ── Filter whitelist: invalid diabaikan, bukan dieksekusi ───

    public function test_index_filter_whitelist_invalid_values_ignored(): void
    {
        $owner = User::factory()->create();
        $project = $this->makeProject($owner);
        Task::create(array_merge($this->validPayload($project), ['title' => 'A pending', 'status' => 'pending', 'priority' => 'low']));
        Task::create(array_merge($this->validPayload($project), ['title' => 'B completed', 'status' => 'completed', 'priority' => 'high']));

        // filter valid tetap jalan
        $this->actingAs($owner)->get(route('tasks.index', ['status' => 'pending']))
            ->assertOk()->assertSee('A pending')->assertDontSee('B completed');

        $this->actingAs($owner)->get(route('tasks.index', ['priority' => 'high']))
            ->assertOk()->assertSee('B completed')->assertDontSee('A pending');

        // filter invalid DIABAIKAN: tampil semua, tidak 500, tidak memfilter via injeksi
        $this->actingAs($owner)->get(route('tasks.index', ['status' => "pending' OR '1'='1"]))
            ->assertOk()->assertSee('A pending')->assertSee('B completed');

        $this->actingAs($owner)->get(route('tasks.index', ['priority' => 'high; DROP TABLE tasks --']))
            ->assertOk()->assertSee('A pending')->assertSee('B completed');

        $this->assertTrue(Schema::hasTable('tasks'));
    }

    // ── SQL injection: disimpan literal, tabel utuh ─────────────

    public function test_sql_injection_payload_stored_as_literal(): void
    {
        $owner = User::factory()->create();
        $project = $this->makeProject($owner);

        $evilTitle = "x' OR '1'='1'; DROP TABLE tasks; --";
        $evilDesc = '"; SELECT * FROM users; --';

        $this->actingAs($owner)
            ->post(route('tasks.store'), array_merge($this->validPayload($project), [
                'title' => $evilTitle,
                'description' => $evilDesc,
            ]))
            ->assertRedirect(route('projects.show', $project->id));

        // tersimpan sebagai literal, bukan dieksekusi
        $this->assertDatabaseHas('tasks', ['title' => $evilTitle, 'description' => $evilDesc]);
        $this->assertTrue(Schema::hasTable('tasks'));
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertSame(1, Task::count());

        // pencarian injeksi juga literal: tidak membocorkan / tidak error
        $this->actingAs($owner)->get(route('tasks.index', ['q' => "' OR '1'='1"]))
            ->assertOk();
        $this->assertTrue(Schema::hasTable('tasks'));
    }

    public function test_search_injection_does_not_leak_other_projects(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $mine = $this->makeProject($owner, 'Milikku');
        $theirs = Project::create(['owner_id' => $stranger->id, 'name' => 'Rahasia', 'description' => null]);

        Task::create(array_merge($this->validPayload($mine), ['title' => 'Tugas milikku']));
        Task::create([
            'project_id' => $theirs->id,
            'title' => 'Tugas rahasia orang lain',
            'description' => null,
            'priority' => 'low',
            'deadline' => null,
            'status' => 'pending',
        ]);

        // Sanity: pencarian normal hanya menemukan milik sendiri.
        $this->actingAs($owner)->get(route('tasks.index', ['q' => 'milikku']))
            ->assertOk()
            ->assertSee('Tugas milikku')
            ->assertDontSee('Tugas rahasia orang lain');

        // q injeksi klasik diperlakukan literal → tidak error, tidak membocorkan
        // task project lain (hasil: kosong, bukan dump semua).
        $this->actingAs($owner)->get(route('tasks.index', ['q' => "' OR '1'='1' --"]))
            ->assertOk()
            ->assertDontSee('Tugas rahasia orang lain');
        $this->assertTrue(Schema::hasTable('tasks'));
    }

    // ── Otorisasi domain task ───────────────────────────────────

    public function test_non_member_cannot_create_or_see_task(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $project = $this->makeProject($owner);

        $this->actingAs($stranger)
            ->post(route('tasks.store'), $this->validPayload($project))
            ->assertForbidden();

        $this->actingAs($stranger)
            ->get(route('projects.tasks.index', $project))
            ->assertForbidden();

        $this->assertSame(0, Task::count());
    }

    // ── Audit statis: tidak ada raw SQL dari input user ─────────

    public function test_audit_no_raw_sql_from_user_input_in_task_controller(): void
    {
        $path = app_path('Http/Controllers/TaskController.php');
        $src = file_get_contents($path);

        // Pola berbahaya yang menandakan input user masuk ke SQL mentah.
        $dangerous = [
            'whereRaw', 'selectRaw', 'havingRaw', 'orderByRaw($',
            'DB::select', 'DB::statement', 'DB::unprepared', 'DB::raw($',
        ];
        foreach ($dangerous as $needle) {
            $this->assertStringNotContainsString($needle, $src, "Ditemukan pola raw SQL berbahaya: {$needle}");
        }

        // orderByRaw yang ada harus string statis CASE priority — tanpa variabel request.
        $this->assertStringContainsString(
            'orderByRaw("CASE priority WHEN',
            $src,
            'Urutan priority harus memakai string statis, bukan input user.'
        );
        // Per baris: baris mana pun yang memuat orderByRaw tidak boleh
        // memuat $request / $_GET / $_POST / $q dari user.
        foreach (explode("\n", $src) as $no => $line) {
            if (str_contains($line, 'orderByRaw')) {
                $this->assertStringNotContainsString('$request', $line, 'orderByRaw di baris '.($no + 1).' tidak boleh memakai $request.');
                $this->assertStringNotContainsString('$_GET', $line);
                $this->assertStringNotContainsString('$_POST', $line);
                $this->assertStringNotContainsString('$_REQUEST', $line);
            }
        }

        // Filter wajib whitelist ketat.
        $this->assertStringContainsString('Task::STATUSES', $src);
        $this->assertStringContainsString('Task::PRIORITIES', $src);
        $this->assertStringContainsString('Rule::in', $src);
    }
}
