<?php

namespace Tests\Feature;

use App\Models\Prestasi;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Prestasi API Tests
 * DEF-003 FIX: Use Sanctum::actingAs() for correct API guard authentication
 */
class PrestasiTest extends TestCase
{
    use RefreshDatabase;

    protected User $mahasiswa;
    protected User $kemahasiswaan;
    protected User $mahasiswaLain;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        Storage::fake('public');

        $this->mahasiswa = User::factory()->create([
            'role' => 'Mahasiswa',
            'is_active' => true,
            'password' => Hash::make('password123'),
        ]);
        $this->mahasiswa->assignRole('Mahasiswa');
        $this->mahasiswa->syncPermissions(config('permissions.role_defaults.Mahasiswa', []));

        $this->mahasiswaLain = User::factory()->create([
            'role' => 'Mahasiswa',
            'is_active' => true,
        ]);
        $this->mahasiswaLain->assignRole('Mahasiswa');
        $this->mahasiswaLain->syncPermissions(config('permissions.role_defaults.Mahasiswa', []));

        $this->kemahasiswaan = User::factory()->create([
            'role' => 'Kemahasiswaan',
            'is_active' => true,
        ]);
        $this->kemahasiswaan->assignRole('Kemahasiswaan');
        $this->kemahasiswaan->syncPermissions(config('permissions.role_defaults.Kemahasiswaan', []));
    }

    private function validPrestasiPayload(): array
    {
        return [
            'nama_kompetisi' => 'Kompetisi Robot Nasional',
            'penyelenggara' => 'Telkom University',
            'tingkat' => 'Nasional',
            'capaian' => 'Juara 1',
            'kategori' => 'Individu',
            'mewakili_ormawa' => 'tidak',
            'dokumen' => [
                [
                    'jenis_dokumen' => 'Sertifikat',
                    'file' => UploadedFile::fake()->create('sertifikat.pdf', 512, 'application/pdf'),
                ]
            ],
        ];
    }

    // =====================================================
    // CREATE PRESTASI
    // =====================================================

    public function test_mahasiswa_can_create_prestasi(): void
    {
        Sanctum::actingAs($this->mahasiswa);

        $response = $this->postJson('/api/v1/prestasi', $this->validPrestasiPayload());

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'data' => ['status_verifikasi' => 'Menunggu'],
            ]);
    }

    public function test_prestasi_created_belongs_to_authenticated_user(): void
    {
        Sanctum::actingAs($this->mahasiswa);

        $response = $this->postJson('/api/v1/prestasi', $this->validPrestasiPayload());

        $response->assertStatus(201);
        $prestasiId = $response->json('data.id_prestasi');

        $this->assertDatabaseHas('prestasi', [
            'id_prestasi' => $prestasiId,
            'id_user' => $this->mahasiswa->id_user,
        ]);
    }

    public function test_create_prestasi_requires_at_least_one_dokumen(): void
    {
        Sanctum::actingAs($this->mahasiswa);
        $payload = $this->validPrestasiPayload();
        unset($payload['dokumen']);

        $response = $this->postJson('/api/v1/prestasi', $payload);

        $response->assertStatus(422);
    }

    public function test_create_prestasi_without_token_returns_401(): void
    {
        $response = $this->postJson('/api/v1/prestasi', $this->validPrestasiPayload());
        $response->assertStatus(401);
    }

    // =====================================================
    // READ PRESTASI
    // =====================================================

    public function test_mahasiswa_can_list_own_prestasi(): void
    {
        Prestasi::factory()->create([
            'id_user' => $this->mahasiswa->id_user,
            'status_verifikasi' => 'Menunggu',
        ]);

        Sanctum::actingAs($this->mahasiswa);
        $response = $this->getJson('/api/v1/prestasi');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'pagination']);
    }

    public function test_mahasiswa_cannot_access_others_prestasi(): void
    {
        $prestasi = Prestasi::factory()->create([
            'id_user' => $this->mahasiswaLain->id_user,
        ]);

        Sanctum::actingAs($this->mahasiswa);
        $response = $this->getJson("/api/v1/prestasi/{$prestasi->id_prestasi}");

        $response->assertStatus(403);
    }

    public function test_kemahasiswaan_can_view_all_prestasi(): void
    {
        Prestasi::factory()->create(['id_user' => $this->mahasiswa->id_user]);
        Prestasi::factory()->create(['id_user' => $this->mahasiswaLain->id_user]);

        Sanctum::actingAs($this->kemahasiswaan);
        $response = $this->getJson('/api/v1/prestasi');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertGreaterThanOrEqual(2, count($data));
    }

    // =====================================================
    // UPDATE PRESTASI
    // =====================================================

    public function test_mahasiswa_can_update_own_menunggu_prestasi(): void
    {
        // Mahasiswa needs 'Edit Prestasi' permission — not in default Mahasiswa config
        // Grant Edit Prestasi for this test explicitly
        $this->mahasiswa->givePermissionTo('Edit Prestasi');

        $prestasi = Prestasi::factory()->create([
            'id_user' => $this->mahasiswa->id_user,
            'status_verifikasi' => 'Menunggu',
        ]);

        Sanctum::actingAs($this->mahasiswa);
        $response = $this->putJson("/api/v1/prestasi/{$prestasi->id_prestasi}", [
            'nama_kompetisi' => 'Updated Competition Name',
            'penyelenggara' => $prestasi->penyelenggara,
            'tingkat' => $prestasi->tingkat,
            'capaian' => $prestasi->capaian,
            'kategori' => $prestasi->kategori,
            'mewakili_ormawa' => $prestasi->mewakili_ormawa ?? 'tidak',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('prestasi', [
            'id_prestasi' => $prestasi->id_prestasi,
            'nama_kompetisi' => 'Updated Competition Name',
        ]);
    }

    public function test_mahasiswa_cannot_update_others_prestasi(): void
    {
        $prestasi = Prestasi::factory()->create([
            'id_user' => $this->mahasiswaLain->id_user,
            'status_verifikasi' => 'Menunggu',
        ]);

        Sanctum::actingAs($this->mahasiswa);
        $response = $this->putJson("/api/v1/prestasi/{$prestasi->id_prestasi}", [
            'nama_kompetisi' => 'Hack Attempt',
        ]);

        $response->assertStatus(403);
    }

    // =====================================================
    // DELETE PRESTASI
    // =====================================================

    public function test_mahasiswa_can_delete_menunggu_prestasi(): void
    {
        // Mahasiswa needs 'Delete Prestasi' permission — grant explicitly for this test
        $this->mahasiswa->givePermissionTo('Delete Prestasi');

        $prestasi = Prestasi::factory()->create([
            'id_user' => $this->mahasiswa->id_user,
            'status_verifikasi' => 'Menunggu',
        ]);

        Sanctum::actingAs($this->mahasiswa);
        $response = $this->deleteJson("/api/v1/prestasi/{$prestasi->id_prestasi}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('prestasi', ['id_prestasi' => $prestasi->id_prestasi]);
    }

    public function test_cannot_delete_valid_prestasi(): void
    {
        // Grant Delete Prestasi permission for this test
        $this->mahasiswa->givePermissionTo('Delete Prestasi');

        $prestasi = Prestasi::factory()->create([
            'id_user' => $this->mahasiswa->id_user,
            'status_verifikasi' => 'Valid',
        ]);

        Sanctum::actingAs($this->mahasiswa);
        $response = $this->deleteJson("/api/v1/prestasi/{$prestasi->id_prestasi}");

        $response->assertStatus(422);
    }

    public function test_mahasiswa_cannot_delete_others_prestasi(): void
    {
        $prestasi = Prestasi::factory()->create([
            'id_user' => $this->mahasiswaLain->id_user,
            'status_verifikasi' => 'Menunggu',
        ]);

        Sanctum::actingAs($this->mahasiswa);
        $response = $this->deleteJson("/api/v1/prestasi/{$prestasi->id_prestasi}");

        $response->assertStatus(403);
    }

    // =====================================================
    // VERIFY PRESTASI
    // =====================================================

    public function test_kemahasiswaan_can_verify_prestasi(): void
    {
        $prestasi = Prestasi::factory()->create([
            'id_user' => $this->mahasiswa->id_user,
            'status_verifikasi' => 'Menunggu',
        ]);

        Sanctum::actingAs($this->kemahasiswaan);
        $response = $this->patchJson("/api/v1/prestasi/{$prestasi->id_prestasi}/verifikasi", [
            'status_verifikasi' => 'Valid',
        ]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['status_verifikasi' => 'Valid']]);
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

    // =====================================================
    // TAMBAH ANGGOTA
    // =====================================================

    public function test_can_add_anggota_to_kelompok_prestasi(): void
    {
        $prestasi = Prestasi::factory()->create([
            'id_user' => $this->mahasiswa->id_user,
            'kategori' => 'Kelompok',
            'status_verifikasi' => 'Menunggu',
        ]);

        Sanctum::actingAs($this->mahasiswa);
        $response = $this->postJson("/api/v1/prestasi/{$prestasi->id_prestasi}/anggota", [
            'nama' => 'Jane Doe',
            'nim' => '202310002',
            'prodi' => 'Teknik Informatika',
        ]);

        $response->assertStatus(201)
            ->assertJson(['data' => ['nama' => 'Jane Doe']]);
    }

    public function test_cannot_add_anggota_to_individu_prestasi(): void
    {
        $prestasi = Prestasi::factory()->create([
            'id_user' => $this->mahasiswa->id_user,
            'kategori' => 'Individu',
        ]);

        Sanctum::actingAs($this->mahasiswa);
        $response = $this->postJson("/api/v1/prestasi/{$prestasi->id_prestasi}/anggota", [
            'nama' => 'Jane Doe',
            'nim' => '202310002',
            'prodi' => 'TI',
        ]);

        $response->assertStatus(422);
    }

    // =====================================================
    // STATUS CHECK & FILTER
    // =====================================================

    public function test_check_status_returns_status(): void
    {
        $prestasi = Prestasi::factory()->create([
            'id_user' => $this->mahasiswa->id_user,
            'status_verifikasi' => 'Menunggu',
        ]);

        Sanctum::actingAs($this->mahasiswa);
        $response = $this->getJson("/api/v1/prestasi/{$prestasi->id_prestasi}/status");

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id_prestasi', 'status_verifikasi']]);
    }

    public function test_can_filter_prestasi_by_tingkat(): void
    {
        Prestasi::factory()->create([
            'id_user' => $this->mahasiswa->id_user,
            'tingkat' => 'Nasional',
        ]);

        Sanctum::actingAs($this->kemahasiswaan);
        $response = $this->getJson('/api/v1/prestasi?tingkat=Nasional');

        $response->assertStatus(200);
    }

    public function test_can_search_prestasi_by_keyword(): void
    {
        Prestasi::factory()->create([
            'id_user' => $this->mahasiswa->id_user,
            'nama_kompetisi' => 'Robot Challenge',
        ]);

        Sanctum::actingAs($this->kemahasiswaan);
        $response = $this->getJson('/api/v1/prestasi?search=Robot');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertGreaterThanOrEqual(1, count($data));
    }
}
