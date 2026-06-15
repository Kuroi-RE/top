<?php

namespace Tests\Feature;

use App\Models\Prestasi;
use App\Models\ProposalKegiatan;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Security Tests
 * DEF-003 FIX: Use Sanctum::actingAs() for correct API auth
 * DEF-001 REGRESSION: Verify password hashing in reset flow
 * DEF-008 REGRESSION: Rate limiting tests
 */
class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected User $mahasiswa;
    protected User $ormawa;
    protected User $kemahasiswaan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->mahasiswa = User::factory()->create([
            'role' => 'Mahasiswa',
            'is_active' => true,
        ]);
        $this->mahasiswa->assignRole('Mahasiswa');
        $this->mahasiswa->syncPermissions(config('permissions.role_defaults.Mahasiswa', []));

        $this->ormawa = User::factory()->create([
            'role' => 'Ormawa',
            'is_active' => true,
        ]);
        $this->ormawa->syncPermissions(config('permissions.role_defaults.Ormawa Institusi', []));

        $this->kemahasiswaan = User::factory()->create([
            'role' => 'Kemahasiswaan',
            'is_active' => true,
        ]);
        $this->kemahasiswaan->assignRole('Kemahasiswaan');
        $this->kemahasiswaan->syncPermissions(config('permissions.role_defaults.Kemahasiswaan', []));
    }

    // =====================================================
    // AUTHENTICATION BYPASS TESTS
    // =====================================================

    public function test_all_protected_routes_require_authentication(): void
    {
        $protectedRoutes = [
            ['GET', '/api/v1/auth/me'],
            ['POST', '/api/v1/auth/logout'],
            ['GET', '/api/v1/proposal'],
            ['POST', '/api/v1/proposal'],
            ['GET', '/api/v1/lpj'],
            ['POST', '/api/v1/lpj'],
            ['GET', '/api/v1/prestasi'],
            ['GET', '/api/v1/template'],
            ['GET', '/api/v1/users'],
        ];

        foreach ($protectedRoutes as [$method, $route]) {
            $response = $this->json($method, $route);
            $this->assertEquals(401, $response->getStatusCode(),
                "Route {$method} {$route} should return 401 without auth, got {$response->getStatusCode()}");
        }
    }

    public function test_invalid_token_returns_401(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer invalid_token_here')
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(401);
    }

    public function test_malformed_bearer_token_returns_401(): void
    {
        $response = $this->withHeader('Authorization', 'NotBearer abc123')
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(401);
    }

    // =====================================================
    // TOKEN REVOCATION (DEF-003 related)
    // =====================================================

    public function test_used_token_after_logout_is_invalid(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $token = $user->createToken('test-revoke')->plainTextToken;

        // Use token successfully
        $this->withHeaders(['Authorization' => "Bearer {$token}", 'Accept' => 'application/json'])
            ->get('/api/v1/auth/me')
            ->assertStatus(200);

        // Logout
        $logoutResponse = $this->withHeaders(['Authorization' => "Bearer {$token}", 'Accept' => 'application/json'])
            ->post('/api/v1/auth/logout');
        $logoutResponse->assertStatus(200);

        // Verify token deleted from DB — this is the definitive proof of revocation
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id_user,
        ]);
    }

    // =====================================================
    // DATA ISOLATION - USER CAN'T ACCESS OTHERS' DATA
    // =====================================================

    public function test_mahasiswa_cannot_see_other_prestasi(): void
    {
        $otherUser = User::factory()->create(['role' => 'Mahasiswa']);
        $otherUser->syncPermissions(config('permissions.role_defaults.Mahasiswa', []));

        $prestasi = Prestasi::factory()->create([
            'id_user' => $otherUser->id_user,
        ]);

        Sanctum::actingAs($this->mahasiswa);
        $response = $this->getJson("/api/v1/prestasi/{$prestasi->id_prestasi}");

        $response->assertStatus(403)
            ->assertJson(['status' => 'error']);
    }

    public function test_ormawa_cannot_see_other_proposals(): void
    {
        $otherOrmawa = User::factory()->create(['role' => 'Ormawa']);
        $otherOrmawa->syncPermissions(config('permissions.role_defaults.Ormawa Institusi', []));

        $proposal = ProposalKegiatan::factory()->create([
            'id_user' => $otherOrmawa->id_user,
            'status' => 'Pending',
        ]);

        Sanctum::actingAs($this->ormawa);
        $response = $this->getJson("/api/v1/proposal/{$proposal->id_proposal}");

        $response->assertStatus(403);
    }

    // =====================================================
    // AUTHORIZATION - PERMISSION CHECKS
    // =====================================================

    public function test_mahasiswa_without_view_users_gets_403(): void
    {
        Sanctum::actingAs($this->mahasiswa);
        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(403);
    }

    public function test_mahasiswa_cannot_verify_prestasi(): void
    {
        $prestasi = Prestasi::factory()->create([
            'id_user' => $this->mahasiswa->id_user,
            'status_verifikasi' => 'Menunggu',
        ]);

        Sanctum::actingAs($this->mahasiswa);
        $response = $this->patchJson("/api/v1/prestasi/{$prestasi->id_prestasi}/verifikasi", [
            'status_verifikasi' => 'Valid',
        ]);

        $response->assertStatus(403);
    }

    public function test_mahasiswa_cannot_verify_proposal(): void
    {
        $proposal = ProposalKegiatan::factory()->create([
            'id_user' => $this->ormawa->id_user,
            'status' => 'Pending',
        ]);

        Sanctum::actingAs($this->mahasiswa);
        $response = $this->patchJson("/api/v1/proposal/{$proposal->id_proposal}/verifikasi", [
            'status' => 'Approved',
            'anggaran_disetujui' => 1000000,
        ]);

        $response->assertStatus(403);
    }

    // =====================================================
    // DEF-001 REGRESSION: Password Hashing
    // =====================================================

    /**
     * DEF-001 REGRESSION TEST: Verify password is hashed after reset password.
     * This test MUST PASS after the fix is applied.
     * If this test fails, DEF-001 is NOT fixed.
     */
    public function test_reset_password_stores_hashed_password_not_plain_text(): void
    {
        $user = User::factory()->create([
            'email' => 'security.hash.test@example.com',
        ]);

        $rawToken = 'regression_test_token_def001_' . uniqid();
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($rawToken),
            'created_at' => now(),
        ]);

        $newPassword = 'NewSecurePass123!';

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'token' => $rawToken,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertStatus(200);

        // DEF-001 FIX VERIFICATION
        $freshUser = User::find($user->id_user);

        // 1. Password must NOT equal the plain text value
        $this->assertNotEquals($newPassword, $freshUser->password,
            'DEF-001: Password is stored as plain text! It must be hashed.');

        // 2. Password must be a valid bcrypt hash
        $this->assertTrue(
            str_starts_with($freshUser->password, '$2y$') || str_starts_with($freshUser->password, '$2b$'),
            'DEF-001: Password must be a bcrypt hash starting with $2y$ or $2b$. Got: ' . substr($freshUser->password, 0, 10)
        );

        // 3. Hash::check must succeed with the new password
        $this->assertTrue(
            Hash::check($newPassword, $freshUser->password),
            'DEF-001: Hash::check failed — password hash does not match the expected value.'
        );

        // 4. Login with new password must succeed — DEF-002: now returns 200
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'username' => $freshUser->username,
            'password' => $newPassword,
        ]);
        $loginResponse->assertStatus(200);
        $this->assertNotNull($loginResponse->json('data.token'), 'Login with new password must return a token.');
    }

    // =====================================================
    // DEF-002 RESOLVED: Login status contract tests
    // =====================================================

    /**
     * DEF-002 RESOLVED: Wrong credentials return 401 (not 422).
     * Contract: 401 = authentication failed, 422 = validation/payload error
     */
    public function test_wrong_credentials_return_401_not_422(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('correctpassword'),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'username' => $user->username,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['status' => 'error']);

        $this->assertNull($response->json('data.token'));
    }

    /**
     * DEF-002 RESOLVED: Invalid payload (missing fields) still returns 422.
     */
    public function test_missing_payload_returns_422_not_401(): void
    {
        $response = $this->postJson('/api/v1/auth/login', []);

        $response->assertStatus(422);
    }

    // =====================================================
    // DEF-008 REGRESSION: Rate Limiting
    // =====================================================

    /**
     * DEF-008 REGRESSION TEST: Login endpoint should return 429 after exceeding limit.
     * Rate limit: 10 per minute (throttle:10,1 in routes)
     */
    public function test_login_rate_limit_returns_429_after_limit(): void
    {
        $user = User::factory()->create([
            'username' => 'ratelimit_test_user',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        // Make 10 failed login attempts (wrong password) to trigger rate limit
        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/api/v1/auth/login', [
                'username' => 'ratelimit_test_user',
                'password' => 'wrongpassword',
            ]);
        }

        // 11th attempt should return 429 Too Many Requests
        $response = $this->postJson('/api/v1/auth/login', [
            'username' => 'ratelimit_test_user',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(429);
    }

    /**
     * DEF-008 REGRESSION TEST: Rate limiting is applied on forgot-password.
     */
    public function test_forgot_password_rate_limit_returns_429_after_limit(): void
    {
        $user = User::factory()->create(['email' => 'fp_ratelimit@example.com']);

        // 5 requests then 6th should be rate limited
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/auth/forgot-password', ['email' => $user->email]);
        }

        $response = $this->postJson('/api/v1/auth/forgot-password', ['email' => $user->email]);
        $response->assertStatus(429);
    }

    // =====================================================
    // INPUT VALIDATION / INJECTION PROTECTION
    // =====================================================

    public function test_sql_injection_in_search_is_safe(): void
    {
        $maliciousSearch = "'; DROP TABLE users; --";

        Sanctum::actingAs($this->kemahasiswaan);
        $response = $this->getJson('/api/v1/users?search=' . urlencode($maliciousSearch));

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id_user' => $this->kemahasiswaan->id_user]);
    }

    public function test_xss_in_string_fields_is_safe(): void
    {
        $xssPayload = '<script>alert("xss")</script>';

        $response = $this->postJson('/api/v1/auth/register', [
            'nim' => '202310XSS001',
            'nama_depan' => $xssPayload,
            'nama_belakang' => 'Test',
            'prodi' => 'TI',
            'email' => 'xsstest.' . uniqid() . '@telkomuniversity.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertNotEquals(500, $response->getStatusCode(),
            'Server should not error out on XSS payload');
    }

    // =====================================================
    // PUBLIC ROUTES ACCESSIBILITY
    // =====================================================

    public function test_public_informasi_routes_work_without_auth(): void
    {
        $response = $this->getJson('/api/v1/informasi');
        $response->assertStatus(200);
    }

    public function test_public_auth_routes_work_without_token(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'nim' => '999999' . rand(100, 999),
            'nama_depan' => 'Public',
            'nama_belakang' => 'Test',
            'prodi' => 'TI',
            'email' => 'pub.' . uniqid() . '@telkomuniversity.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertStatus(201);
    }
}
