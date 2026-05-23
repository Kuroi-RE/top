<?php

namespace Database\Factories;

use App\Models\InformasiKegiatan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InformasiKegiatanFactory extends Factory
{
    protected $model = InformasiKegiatan::class;

    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'judul' => fake()->sentence(),
            'role' => fake()->randomElement(['Ormawa', 'Kemahasiswaan']),
            'caption' => fake()->paragraph(),
            'file' => null,
        ];
    }

    public function forOrmawa(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'Ormawa',
            'id_user' => User::factory()->ormawa(),
        ]);
    }

    public function forKemahasiswaan(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'Kemahasiswaan',
            'id_user' => User::factory()->admin(),
        ]);
    }
}
