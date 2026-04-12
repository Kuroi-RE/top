<?php

namespace Database\Factories;

use App\Models\LpjKegiatan;
use App\Models\ProposalKegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class LpjKegiatanFactory extends Factory
{
    protected $model = LpjKegiatan::class;

    public function definition(): array
    {
        return [
            'id_proposal' => fn () => ProposalKegiatan::factory()->create()->id_proposal,
            'file_lpj' => 'lpj/sample-lpj.pdf',
            'status_lpj' => 'Menunggu',
            'tanggal_upload' => fake()->dateTime(),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_lpj' => 'Disetujui',
        ]);
    }

    public function needsRevision(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_lpj' => 'Revisi',
        ]);
    }
}
