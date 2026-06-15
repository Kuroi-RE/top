<?php

namespace Database\Factories;

use App\Models\ProposalKegiatan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProposalKegiatanFactory extends Factory
{
    protected $model = ProposalKegiatan::class;

    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'ajuan_triwulan' => fake()->randomElement(['I', 'II', 'III', 'IV']),
            'risiko_proposal' => fake()->randomElement(['Rendah', 'Sedang', 'Tinggi']),
            'no_telepon' => fake()->numerify('08##########'),
            'nama_kegiatan' => fake()->sentence(4),
            'waktu_kegiatan' => fake()->dateTimeBetween('now', '+3 months'),
            'tempat_kegiatan' => fake()->city(),
            'besar_ajuan' => fake()->numberBetween(500000, 50000000),
            'nomor_rekening' => fake()->numerify('##############'),
            'nama_rekening' => fake()->name(),
            'nama_bank' => fake()->randomElement(['Bank BCA', 'Bank Mandiri', 'Bank BNI', 'Bank BTN']),
            'honor_pelatih' => fake()->randomElement(['Ya', 'Tidak']),
            'file' => 'proposals/sample-proposal.pdf',
            // DEF-004 FIX: Use canonical English status values consistent with controllers
            'status' => 'Pending',
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            // DEF-004 FIX: English status values
            'status' => 'Approved',
            'anggaran_disetujui' => $attributes['besar_ajuan'] * fake()->randomFloat(2, 0.5, 1.0),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            // DEF-004 FIX: English status values
            'status' => 'Rejected',
            'catatan_admin' => 'Proposal ditolak karena tidak memenuhi kriteria',
        ]);
    }

    public function needsRevision(): static
    {
        return $this->state(fn (array $attributes) => [
            // DEF-004 FIX: English status values
            'status' => 'Revision',
            'catatan_admin' => 'Silakan lakukan revisi sesuai catatan',
        ]);
    }
}
