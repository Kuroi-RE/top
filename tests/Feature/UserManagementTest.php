<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * User Management Tests
 * DEF-003 FIX: Use Sanctum::actingAs() instead of $this->actingAs($user, 'sanctum')
 * Routes use auth:api guard with sanctum driver — Sanctum::actingAs bypasses guard lookup.
 */
class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $kemahasiswaan;
    protected User $mahasiswa;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->superAdmin = User::factory()->create([
            'role' => 'Super Admin',
            'is_active' => true,
            'password' => Hash::make('password123'),
        ]);
        $this->superAdmin->assignRole('Super Admin');
        $this->superAdmin->syncPermissions(config('permissions.role_defaults.Super Admin', []));

        $this->kemahasiswaan = User::factory()->create([
            'role' => 'Kemahasiswaan',
            'is_active' => true,
            'password' => Hash::make('password123'),
        ]);
        $this->kemahasiswaan->assignRole('Kemahasiswaan');
        $this->kemahasiswaan->syncPermissions(config('permissions.role_defaults.Kemahasiswaan', []));

        $this->mahasiswa = User::factory()->create([
            'role' => 'Mahasiswa',
            'is_active' => true,
            'password' => Hash::make('password123'),
        ]);
        $this->mahasiswa->assignRole('Mahasiswa');
        $this->mahasiswa->syncPermissions(config('permissions.role_defaults.Mahasiswa', []));
    }

    // =====================================================
    // LIST USERS
    // =====================================================

    public function test_kemahasiswaan_can_list_users(): void
    {
        Sanctum::actingAs($this->kemahasiswaan);

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'pagination',
            ]);
    }

    public function test_mahasiswa_cannot_list_users_without_permission(): void
    {
        // Mahasiswa doesn't have 'View Users' permission
        Sanctum::actingAs($this->mahasiswa);

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_list_users(): void
    {
        $response = $this->getJson('/api/v1/users');
        $response->assertStatus(401);
    }

    // =====================================================
    // FILTER USERS
    // =====================================================

    public function test_can_filter_users_by_role(): void
    {
        Sanctum::actingAs($this->kemahasiswaan);

        $response = $this->getJson('/api/v1/users?role=Mahasiswa');

        $response->assertStatus(200);
    }

    public function test_can_search_users(): void
    {
        Sanctum::actingAs($this->kemahasiswaan);

        $response = $this->getJson('/api/v1/users?search=test');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'pagination']);
    }

    // =====================================================
    // GET SINGLE USER
    // =====================================================

    public function test_admin_can_view_user_detail(): void
    {
        Sanctum::actingAs($this->kemahasiswaan);

        $response = $this->getJson("/api/v1/users/{$this->mahasiswa->id_user}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => ['id_user' => $this->mahasiswa->id_user],
            ]);
    }

    public function test_view_non_existent_user_returns_404(): void
    {
        Sanctum::actingAs($this->kemahasiswaan);

        $response = $this->getJson('/api/v1/users/99999');

        $response->assertStatus(404);
    }

    // =====================================================
    // TOGGLE ACCESS
    // =====================================================

    public function test_admin_can_deactivate_user(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        Sanctum::actingAs($this->kemahasiswaan);

        $response = $this->patchJson("/api/v1/users/{$user->id_user}/toggle-akses");

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id_user' => $user->id_user,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_reactivate_user(): void
    {
        $user = User::factory()->create(['is_active' => false]);
        Sanctum::actingAs($this->kemahasiswaan);

        $response = $this->patchJson("/api/v1/users/{$user->id_user}/toggle-akses");

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id_user' => $user->id_user,
            'is_active' => true,
        ]);
    }

    /**
     * DEF-006 + DEF-002 regression test: Deactivated user must NOT be able to login.
     * DEF-002 FIX: Inactive account now returns 403 (forbidden), not 422.
     */
    public function test_deactivated_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'username' => 'deactivated_user_test',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        // Deactivate via toggle (kemahasiswaan doing the action)
        Sanctum::actingAs($this->kemahasiswaan);
        $toggleResponse = $this->patchJson("/api/v1/users/{$user->id_user}/toggle-akses");
        $toggleResponse->assertStatus(200);

        // Verify is_active is now false in DB
        $this->assertDatabaseHas('users', [
            'id_user' => $user->id_user,
            'is_active' => false,
        ]);

        // Try to login — should be rejected because is_active = false
        // DEF-002 FIX: inactive user now returns 403 (not 422)
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'username' => 'deactivated_user_test',
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(403);
        $this->assertNull($loginResponse->json('data.token') ?? null);
    }

    // =====================================================
    // ASSIGN ROLE
    // =====================================================

    public function test_kemahasiswaan_can_assign_ormawa_role(): void
    {
        $targetUser = User::factory()->create(['role' => 'Mahasiswa']);
        Sanctum::actingAs($this->kemahasiswaan);

        $response = $this->patchJson("/api/v1/users/{$targetUser->id_user}/assign-role", [
            'role' => 'Ormawa',
            'ormawa_type' => 'institusi',
            'ormawa_name' => 'BEMF',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'role' => 'Ormawa',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id_user' => $targetUser->id_user,
            'role' => 'Ormawa',
            'ormawa_name' => 'BEMF',
        ]);
    }

    public function test_assigning_role_syncs_permissions(): void
    {
        $targetUser = User::factory()->create(['role' => 'Mahasiswa']);
        Sanctum::actingAs($this->kemahasiswaan);

        $response = $this->patchJson("/api/v1/users/{$targetUser->id_user}/assign-role", [
            'role' => 'Ormawa',
            'ormawa_type' => 'institusi',
            'ormawa_name' => 'BEMF',
        ]);

        $response->assertStatus(200);

        // Verify role was updated in DB
        $this->assertDatabaseHas('users', [
            'id_user' => $targetUser->id_user,
            'role' => 'Ormawa',
        ]);
    }

    // =====================================================
    // DELETE USER
    // =====================================================

    public function test_admin_can_delete_user(): void
    {
        $user = User::factory()->create();
        $userId = $user->id_user;

        // SuperAdmin has Delete Users — but route requires BOTH 'View Users' AND 'Delete Users'
        // (nested middleware: outer 'View Users', inner 'Delete Users')
        // superAdmin is already setup with all permissions in setUp()
        // Add explicit debug assertion
        $perms = $this->superAdmin->getAllPermissions()->pluck('name')->toArray();
        $this->assertContains('View Users', $perms, 'SuperAdmin should have View Users permission');
        $this->assertContains('Delete Users', $perms, 'SuperAdmin should have Delete Users permission');

        Sanctum::actingAs($this->superAdmin);
        $response = $this->deleteJson("/api/v1/users/{$userId}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id_user' => $userId]);
    }

    // =====================================================
    // PERMISSIONS MANAGEMENT
    // =====================================================

    public function test_admin_can_get_user_permissions(): void
    {
        Sanctum::actingAs($this->kemahasiswaan);

        $response = $this->getJson("/api/v1/users/{$this->mahasiswa->id_user}/permissions");

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_admin_can_get_all_roles(): void
    {
        Sanctum::actingAs($this->kemahasiswaan);

        $response = $this->getJson('/api/v1/users/spatie/roles');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_admin_can_get_all_permissions(): void
    {
        Sanctum::actingAs($this->kemahasiswaan);

        $response = $this->getJson('/api/v1/users/spatie/permissions');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }
}
