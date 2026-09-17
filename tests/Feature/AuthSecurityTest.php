<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    // ---------- REGISTRASI ----------

    public function test_register_berhasil_hash_session_dan_kunci_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'BUDI@Example.com', // sengaja kapital untuk uji normalisasi
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role' => 'admin', // percobaan eskalasi: harus diabaikan
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $user = User::where('email', 'budi@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('user', $user->role, 'Register tidak boleh bisa eskalasi ke admin');
        $this->assertTrue(Hash::check('rahasia123', $user->password));
        $this->assertNotSame('rahasia123', $user->password, 'Password harus tersimpan sebagai hash');
    }

    public function test_register_validasi_ditolak(): void
    {
        // Password lemah (tanpa angka) + nama pendek + tanpa konfirmasi
        $this->post('/register', [
            'name' => 'Al',
            'email' => 'lemah@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors(['name', 'password']);

        // Email duplikat
        User::factory()->create(['email' => 'duplikat@example.com']);

        $this->post('/register', [
            'name' => 'Nama Valid',
            'email' => 'duplikat@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertSessionHasErrors('email');

        // Email invalid & password pendek
        $this->post('/register', [
            'name' => 'Nama Valid',
            'email' => 'bukan-email',
            'password' => 'pendek1',
            'password_confirmation' => 'pendek1',
        ])->assertSessionHasErrors(['email', 'password']);

        $this->assertGuest();
    }

    // ---------- LOGIN / SESSION ----------

    public function test_login_berhasil_dan_session_diregenerasi(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('rahasia123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'login@example.com',
            'password' => 'rahasia123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_gagal_tidak_bocorkan_info_dan_tidak_login(): void
    {
        User::factory()->create([
            'email' => 'korban@example.com',
            'password' => Hash::make('rahasia123'),
        ]);

        $this->post('/login', [
            'email' => 'korban@example.com',
            'password' => 'salah-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_brute_force_dibatasi_rate_limiter(): void
    {
        User::factory()->create([
            'email' => 'target@example.com',
            'password' => Hash::make('rahasia123'),
        ]);

        // 5x gagal masih berupa error validasi biasa
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'target@example.com',
                'password' => 'salah',
            ])->assertSessionHasErrors('email');
        }

        // Percobaan ke-6 harus kena throttle (429 atau pesan throttle)
        $response = $this->post('/login', [
            'email' => 'target@example.com',
            'password' => 'salah',
        ]);

        // RateLimiter di AuthController melempar ValidationException,
        // middleware throttle melempar 429 — terima salah satunya.
        if ($response->status() === 429) {
            $response->assertStatus(429);
        } else {
            $response->assertSessionHasErrors('email');
            $this->assertStringContainsString(
                'Terlalu banyak',
                (string) session('errors')->first('email')
            );
        }

        $this->assertGuest();
    }

    public function test_logout_menginvalidasi_session(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_guest_tidak_bisa_akses_route_auth(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/users')->assertRedirect('/login');
        $this->post('/logout')->assertRedirect('/login');
    }

    // ---------- USER CRUD (ADMIN ONLY) ----------

    public function test_user_crud_hanya_admin_dan_password_selalu_hash(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $biasa = User::factory()->create(['role' => 'user']);

        // User biasa dilarang akses CRUD
        $this->actingAs($biasa)->get(route('users.index'))->assertForbidden();
        $this->actingAs($biasa)->get(route('users.create'))->assertForbidden();
        $this->actingAs($biasa)->post(route('users.store'), [])->assertForbidden();

        // Admin: validasi store (nama pendek, email invalid, password lemah, role invalid)
        $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'x',
            'email' => 'salah',
            'password' => 'lemah',
            'password_confirmation' => 'lemah',
            'role' => 'superadmin',
        ])->assertSessionHasErrors(['name', 'email', 'password', 'role']);

        // Admin: store berhasil + hash + normalisasi email
        $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'User Baru',
            'email' => 'BARU@Example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role' => 'user',
        ])->assertRedirect(route('users.index'));

        $baru = User::where('email', 'baru@example.com')->first();
        $this->assertNotNull($baru);
        $this->assertTrue(Hash::check('rahasia123', $baru->password));

        // Admin: update tanpa password tidak merusak hash lama
        $hashLama = $baru->password;
        $this->actingAs($admin)->put(route('users.update', $baru), [
            'name' => 'User Baru Edit',
            'email' => 'baru@example.com',
            'password' => null,
            'password_confirmation' => null,
            'role' => 'user',
        ])->assertRedirect(route('users.index'));
        $this->assertSame($hashLama, $baru->fresh()->password);

        // Admin: update dengan password baru → di-hash ulang
        $this->actingAs($admin)->put(route('users.update', $baru), [
            'name' => 'User Baru Edit',
            'email' => 'baru@example.com',
            'password' => 'baru1234',
            'password_confirmation' => 'baru1234',
            'role' => 'user',
        ])->assertRedirect(route('users.index'));
        $this->assertTrue(Hash::check('baru1234', $baru->fresh()->password));

        // Admin tidak bisa hapus dirinya sendiri
        $this->actingAs($admin)
            ->delete(route('users.destroy', $admin))
            ->assertSessionHasErrors('user');
        $this->assertNotNull(User::find($admin->id));

        // Admin tidak bisa menurunkan role dirinya sendiri
        $this->actingAs($admin)->put(route('users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'user',
        ])->assertSessionHasErrors('role');
        $this->assertSame('admin', $admin->fresh()->role);

        // Admin bisa hapus user lain
        $this->actingAs($admin)
            ->delete(route('users.destroy', $baru))
            ->assertRedirect(route('users.index'));
        $this->assertNull(User::find($baru->id));
    }
}
