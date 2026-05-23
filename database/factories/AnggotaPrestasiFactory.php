<?php

namespace Database\Factories;

use App\Models\AnggotaPrestasi;
use App\Models\Prestasi;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnggotaPrestasiFactory extends Factory
{
    protected $model = AnggotaPrestasi::class;

    public function definition(): array
    {
        return [
            'id_prestasi' => Prestasi::factory(),
            'nama' => fake()->name(),
            'nim' => fake()->numerify('##########'),
            'prodi' => fake()->randomElement(['Teknik Informatika', 'Teknik Elektro', 'Manajemen Informatika', 'Sistem Informasi']),
        ];
    }
}
