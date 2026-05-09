<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProposalKegiatan;
use App\Models\LpjKegiatan;
use App\Models\Prestasi;
use App\Models\DokumenPrestasi;
use App\Models\AnggotaPrestasi;
use App\Models\TemplateDokumen;
use App\Models\InformasiKegiatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RoleAndPermissionSeeder::class);

        // Create Super Admin User
        $superAdmin = User::factory()->superAdmin()->create([
            'username' => 'superadmin',
            'nim' => '00000001',
            'nama_depan' => 'Super',
            'nama_belakang' => 'Admin',
            'prodi' => 'Administrator',
            'email' => 'superadmin@top-kema.com',
            'password' => Hash::make('superadmin123'),
        ]);

        // Create Kemahasiswaan (Admin) User
        $admin = User::factory()->admin()->create([
            'username' => 'kemahasiswaan',
            'nim' => '00000002',
            'nama_depan' => 'Dinas',
            'nama_belakang' => 'Kemahasiswaan',
            'prodi' => 'Administrator',
            'email' => 'kemahasiswaan@top-kema.com',
            'password' => Hash::make('kemahasiswaan123'),
        ]);

        // Create DPMBEM User
        $dpmbem = User::factory()->dpmbem()->create([
            'username' => 'dpmbem',
            'nim' => '00000003',
            'nama_depan' => 'Dewan',
            'nama_belakang' => 'Prestasi',
            'prodi' => 'Administrator',
            'email' => 'dpmbem@top-kema.com',
            'password' => Hash::make('dpmbem123'),
        ]);

        // Create Ormawa Institusi (UKM) Users
        $ormawaInstitusis = [];
        $ukmNames = ['BEMF', 'WELL', 'Menara'];
        for ($i = 0; $i < 3; $i++) {
            $ormawaInstitusis[] = User::factory()
                ->ormawaInstitusi($ukmNames[$i] ?? 'UKM ' . ($i + 1))
                ->create([
                    'username' => 'ukm' . ($i + 1),
                    'nim' => '20000' . ($i + 1),
                    'nama_depan' => 'UKM',
                    'nama_belakang' => $ukmNames[$i],
                    'prodi' => 'Berbagai',
                    'email' => 'ukm' . ($i + 1) . '@top-kema.com',
                    'password' => Hash::make('password123'),
                ]);
        }

        // Create Ormawa Prodi (Himpunan) Users
        $ormawaProdi = [];
        $prodiNames = ['HIMA TIF', 'HIMA TE', 'HIMA MI'];
        for ($i = 0; $i < 3; $i++) {
            $ormawaProdi[] = User::factory()
                ->ormawaProdi($prodiNames[$i] ?? 'Himpunan Prodi ' . ($i + 1))
                ->create([
                    'username' => 'prodi' . ($i + 1),
                    'nim' => '30000' . ($i + 1),
                    'nama_depan' => 'Himpunan',
                    'nama_belakang' => $prodiNames[$i],
                    'prodi' => $prodiNames[$i],
                    'email' => 'himpunan' . ($i + 1) . '@top-kema.com',
                    'password' => Hash::make('password123'),
                ]);
        }

        // Create Mahasiswa Users
        $mahasiswaUsers = User::factory(5)->mahasiswa()->create();

        // Combine Ormawa for proposal/lpj generation
        $allOrmawa = array_merge($ormawaInstitusis, $ormawaProdi);

        // Create Proposal Kegiatan for Ormawa
        foreach ($allOrmawa as $ormawa) {
            ProposalKegiatan::factory(2)
                ->for($ormawa, 'user')
                ->create();

            // Create one approved proposal with LPJ
            $approvedProposal = ProposalKegiatan::factory()
                ->approved()
                ->for($ormawa, 'user')
                ->create();

            LpjKegiatan::factory()
                ->for($approvedProposal, 'proposal')
                ->approved()
                ->create();
        }

        // Create Prestasi with Dokumen for Mahasiswa
        foreach ($mahasiswaUsers as $mahasiswa) {
            $prestasi = Prestasi::factory(1)
                ->for($mahasiswa, 'user')
                ->create();

            foreach ($prestasi as $p) {
                DokumenPrestasi::factory(2)->for($p)->create();

                if ($p->kategori === 'Kelompok') {
                    AnggotaPrestasi::factory(2)->for($p)->create();
                }
            }
        }

        // Create Template Dokumen
        TemplateDokumen::factory(3)->create();

        // Create Informasi Kegiatan
        InformasiKegiatan::factory(3)->forOrmawa()->create();
        InformasiKegiatan::factory(2)->forKemahasiswaan()->create();
    }
}
