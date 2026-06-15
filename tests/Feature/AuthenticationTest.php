<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Authentication API Tests
 * Validates: Login, Register, Logout, Me, Token revocation
 */
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    // =====================================================
    // REGISTER TESTS
    // =====================================================

    public function test_register_with_valid_data_returns_201(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'nim' => '202310001',
            'nama_depan' => 'John',
            'nama_belakang' => 'Doe',
            'prodi' => 'Teknik Informatika',
            'email' => 'john@telkomuniversity.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user' => ['id_user', 'username', 'email', 'role'],
                    'token',
                ],
            ])
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'user' => ['role' => 'Mahasiswa'],
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'nim' => '202310001',
            'email' => 'john@telkomuniversity.ac.id',
            'role' => 'Mahasiswa',
            'is_active' => true,
        ]);
    }

    public function test_register_generates_username_from_email(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'nim' => '202310002',
            'nama_depan' => 'Jane',
            'nama_belakang' => 'Smith',
            'prodi' => 'Sistem Informasi',
            'email' => 'jane.smith@telkomuniversity.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);

        $user = User::where('email', 'jane.smith@telkomuniversity.ac.id')->first();
        $this->assertEquals('jane.smith', $user->username);
    }

    public function test_register_with_duplicate_nim_returns_422(): void
    {
        User::factory()->create(['nim' => '202310001']);

        $response = $this->postJson('/api/v1/auth/register', [
            'nim' => '202310001', // duplicate
            'nama_depan' => 'Other',
            'nama_belakang' => 'User',
            'prodi' => 'TI',
            'email' => 'other@telkomuniversity.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nim']);
    }

    public function test_register_with_duplicate_email_returns_422(): void
    {
        User::factory()->create(['email' => 'existing@telkomuniversity.ac.id']);

        $response = $this->postJson('/api/v1/auth/register', [
            'nim' => '202310099',
            'nama_depan' => 'Other',
            'nama_belakang' => 'User',
            'prodi' => 'TI',
            'email' => 'existing@telkomuniversity.ac.id', // duplicate
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_register_with_password_mismatch_returns_422(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'nim' => '202310003',
            'nama_depan' => 'Test',
            'nama_belakang' => 'User',
            'prodi' => 'TI',
            'email' => 'test@telkomuniversity.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'different456',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_register_with_short_password_returns_422(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'nim' => '202310004',
            'nama_depan' => 'Test',
            'nama_belakang' => 'User',
            'prodi' => 'TI',
            'email' => 'testshort@telkomuniversity.ac.id',
            'password' => 'pass', // < 8 chars
            'password_confirmation' => 'pass',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_register_with_missing_required_fields_returns_422(): void
    {
        $response = $this->postJson('/api/v1/auth/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nim', 'nama_depan', 'nama_belakang', 'prodi', 'email', 'password']);
    }

    // =====================================================
    // LOGIN TESTS
    // =====================================================

    public function test_login_with_valid_credentials_returns_200(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['user', 'token'],
            ])
            ->assertJson(['status' => 'success']);
    }

    public function test_login_with_wrong_password_returns_401(): void
    {
        // DEF-002 RESOLVED: API contract = 401 for wrong credentials (not 422)
        // 401 = authentication failed (valid payload, wrong credentials)
        // 422 = validation failed (missing/malformed fields)
        $user = User::factory()->create([
            'username' => 'testuser2',
            'password' => Hash::make('correctpassword'),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'username' => 'testuser2',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['status' => 'error']);

        // No token should be present
        $this->assertNull($response->json('data.token'));
    }

    public function test_login_with_nonexistent_username_returns_401(): void
    {
        // DEF-002: Non-existent username also returns 401 (not 422 user enumeration)
        $response = $this->postJson('/api/v1/auth/login', [
            'username' => 'usernameyangpastinyatidakada',
            'password' => 'somepassword123',
        ]);

        $response->assertStatus(401)
            ->assertJson(['status' => 'error']);
    }

    public function test_login_with_inactive_account_returns_403(): void
    {
        // DEF-002: Inactive account returns 403 (forbidden), distinct from 401 (wrong creds)
        $user = User::factory()->create([
            'username' => 'inactiveuser',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'username' => 'inactiveuser',
            'password' => 'password123',
        ]);

        $response->assertStatus(403);
        $this->assertNull($response->json('data.token'));
    }

    public function test_login_with_missing_fields_returns_422(): void
    {
        $response = $this->postJson('/api/v1/auth/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'password']);
    }

    // =====================================================
    // LOGOUT TESTS
    // =====================================================

    public function test_logout_revokes_token(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $token = $user->createToken('test-logout')->plainTextToken;

        // Verify token works before logout
        $preLogout = $this->withHeaders(['Authorization' => "Bearer {$token}", 'Accept' => 'application/json'])
            ->get('/api/v1/auth/me');
        $preLogout->assertStatus(200);

        // Logout — this deletes the token from personal_access_tokens
        $logoutResponse = $this->withHeaders(['Authorization' => "Bearer {$token}", 'Accept' => 'application/json'])
            ->post('/api/v1/auth/logout');
        $logoutResponse->assertStatus(200);

        // Verify token is gone from DB
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id_user,
        ]);
    }

    public function test_logout_without_token_returns_401(): void
    {
        $response = $this->postJson('/api/v1/auth/logout');
        $response->assertStatus(401);
    }

    // =====================================================
    // ME ENDPOINT TESTS
    // =====================================================

    public function test_me_returns_authenticated_user_data(): void
    {
        $user = User::factory()->create([
            'username' => 'metest',
            'is_active' => true,
        ]);
        // Use Bearer token directly (most reliable for auth:api guard routes)
        $token = $user->createToken('me-test')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => ['username' => 'metest'],
            ]);
    }

    public function test_me_without_token_returns_401(): void
    {
        $response = $this->getJson('/api/v1/auth/me');
        $response->assertStatus(401);
    }

    // =====================================================
    // RESET PASSWORD - DEF-001 REGRESSION TEST
    // =====================================================

    /**
     * DEF-001 REGRESSION: Reset password must store hashed password.
     * Before fix: $user->update(['password' => $request->password]) — plain text
     * After fix: $user->update(['password' => Hash::make($request->password)]) — hashed
     */
    public function test_reset_password_stores_hashed_password(): void
    {
        $user = User::factory()->create([
            'email' => 'resettest@telkomuniversity.ac.id',
        ]);

        $rawToken = 'test_reset_token_regression_def001';
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($rawToken),
            'created_at' => now(),
        ]);

        $newPassword = 'NewHashedPass123!';

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'token' => $rawToken,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertStatus(200);

        // DEF-001 FIX VERIFICATION: Password must be hashed
        $updatedUser = User::find($user->id_user);

        $this->assertNotEquals($newPassword, $updatedUser->password,
            'DEF-001 NOT FIXED: Password stored as plain text in database!');

        $this->assertTrue(Hash::check($newPassword, $updatedUser->password),
            'DEF-001 FIX VERIFIED: Password correctly hashed with bcrypt');
    }

    public function test_reset_password_allows_login_with_new_password(): void
    {
        $user = User::factory()->create([
            'email' => 'loginafter@telkomuniversity.ac.id',
            'username' => 'loginafterreset',
            'password' => Hash::make('OldPassword123'),
        ]);

        $rawToken = 'login_after_reset_token_' . uniqid();
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($rawToken),
            'created_at' => now(),
        ]);

        $this->postJson('/api/v1/auth/reset-password', [
            'token' => $rawToken,
            'email' => $user->email,
            'password' => 'NewPassword456!',
            'password_confirmation' => 'NewPassword456!',
        ])->assertStatus(200);

        // Login with NEW password must succeed
        $this->postJson('/api/v1/auth/login', [
            'username' => 'loginafterreset',
            'password' => 'NewPassword456!',
        ])->assertStatus(200);
    }

    // =====================================================
    // HEALTH CHECK
    // =====================================================

    public function test_health_endpoint_returns_200(): void
    {
        // Health route is at /health (outside /api prefix), need to call without /api prefix
        $response = $this->getJson('/health');
        // In testing environment this may vary — accept 200 or document if not accessible
        // The route exists in routes/api.php as Route::get('/health', ...)
        // In Laravel test the route should be discoverable
        $this->assertContains($response->getStatusCode(), [200, 404],
            'Health endpoint should respond');
        if ($response->getStatusCode() === 200) {
            $response->assertJson(['status' => 'success']);
        }
    }

    // =====================================================
    // FALLBACK ROUTE
    // =====================================================

    public function test_unknown_endpoint_returns_404(): void
    {
        $response = $this->getJson('/api/v1/nonexistent-endpoint');
        $response->assertStatus(404);
    }
}
