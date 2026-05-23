<?php

namespace Database\Factories;

use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrestasiFactory extends Factory
{
    protected $model = Prestasi::class;

    public function definition(): array
    {
        return [
            'id_user' => User::factory()->mahasiswa(),
            'nama_kompetisi' => fake()->sentence(3),
            'penyelenggara' => fake()->company(),
            'tingkat' => fake()->randomElement(['Regional', 'Nasional', 'Internasional']),
            'capaian' => fake()->randomElement(['Juara 1', 'Juara 2', 'Juara 3', 'Top 10', 'Finalist']),
            'kategori' => fake()->randomElement(['Individu', 'Kelompok']),
            'status_verifikasi' => 'Menunggu',
        ];
    }

    public function valid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_verifikasi' => 'Valid',
        ]);
    }

    public function invalid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_verifikasi' => 'Tidak Valid',
        ]);
    }

    public function needsRevision(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_verifikasi' => 'Revisi',
        ]);
    }
}
